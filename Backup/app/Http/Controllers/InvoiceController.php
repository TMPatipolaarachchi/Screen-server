<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Tank;
use App\Models\StockLog;
use App\Models\SupplierBalanceLog;
use App\Models\Payment;
use App\Models\InvoicePaymentSetoff;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $query = Invoice::with(['supplier', 'paymentSetoffs'])
            ->whereHas('supplier', function($query) {
                $query->where('status', 'active');
            });

            // Supplier filter from request
        $supplierId = $request->get('supplier_id');

        // Apply supplier filter if provided
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        // Date filter
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $statusFilter = $request->get('status', 'pending');

        $now = now();
        $currentMonthStart = $now->copy()->startOfMonth()->toDateString();
        $currentMonthEnd = $now->copy()->endOfMonth()->toDateString();

        if ($startDate && $endDate) {
            $query->whereBetween('invoice_date', [$startDate, $endDate]);
            if ($statusFilter && in_array($statusFilter, ['pending', 'complete', 'deleted'])) {
                $query->where('status', $statusFilter);
            }
        } else {
            if ($statusFilter === 'pending') {
                // Show all pending invoices regardless of date
                $query->where('status', 'pending');
            } elseif ($statusFilter === 'complete' || $statusFilter === 'deleted') {
                // Only show current month complete/deleted invoices
                $query->where('status', $statusFilter)
                      ->whereBetween('invoice_date', [$currentMonthStart, $currentMonthEnd]);
            }
        }

        $invoices = $query->latest()->get();

        // Auto-complete invoices with remaining amount less than 1
        foreach ($invoices as $invoice) {
            $paidAmount = round($invoice->paid_amount ?? 0, 2);
            $remainingAmount = round($invoice->total_amount - $paidAmount, 2);
            if ($remainingAmount < 1 && $invoice->status === 'pending') {
                $invoice->update(['status' => 'complete']);
            }
        }

        // Get counts for filter buttons (all time)
        $allInvoices = Invoice::with(['supplier', 'paymentSetoffs'])
            ->whereHas('supplier', function($query) {
                $query->where('status', 'active');
            })
            ->get();

        $pendingCount = $allInvoices->where('status', 'pending')->count();
        $completeCount = $allInvoices->where('status', 'complete')->count();
        $deletedCount = $allInvoices->where('status', 'deleted')->count();

        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        return view('invoices.index', compact('invoices', 'suppliers', 'statusFilter', 'pendingCount', 'completeCount', 'deletedCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $suppliers = Supplier::where('status', 'active')->get();
        $items = Item::where('status', 'active')->get();
        $tanks = Tank::where('status', 'active')->with('item')->get();
        $vehicles = \App\Models\Vehicle::where('status', 'active')->get();
        $employees = \App\Models\Employee::where('status', 'active')->where('role', 'driver')->get();
        $helpers = \App\Models\Employee::where('status', 'active')->where('role', 'helper')->get();
        $invoiceNumber = Invoice::generateInvoiceNumber();
        $defaultVat = Setting::get('vat_percentage', 0);
        
        return view('invoices.create', compact('suppliers', 'items', 'tanks', 'vehicles', 'employees', 'helpers', 'invoiceNumber', 'defaultVat'));
    }

    /**
     * Display setoff management page with all invoices
     */
    public function setoffManagement()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get all pending invoices with their suppliers and payment setoffs
        $invoices = Invoice::with(['supplier', 'paymentSetoffs.payment'])
            ->whereHas('supplier', function($query) {
                $query->where('status', 'active');
            })
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        return view('invoices.setoff-management', compact('invoices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.tank_id' => 'nullable|exists:tanks,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        // Calculate amounts
        $subtotal = round(collect($request->items)->sum('total'), 2);
        $vatPercentage = Setting::get('vat_percentage', 0);
        
        // Calculate VAT per item (only for items with vat_available = true)
        $totalVatAmount = 0;
        foreach ($request->items as $itemData) {
            $item = Item::findOrFail($itemData['item_id']);
            if ($item->vat_available) {
                $itemVat = round(($itemData['total'] * $vatPercentage) / 100, 2);
                $totalVatAmount += $itemVat;
            }
        }
        $vatAmount = round($totalVatAmount, 2);
        
        $bankCharge = round($request->bank_charge ?? 0, 2);
        $totalAmount = round($subtotal + $vatAmount + $bankCharge, 2);
        
        // Check if user wants to add payments
        if ($request->has('add_payments') && $request->add_payments == '1') {
            // Store invoice data in session to pass to setoff page
            $invoiceData = [
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'supplier_id' => $request->supplier_id,
                'vehicle_id' => $request->vehicle_id,
                'employee_id' => $request->employee_id,
                'helper_id' => $request->helper_id,
                'subtotal' => $subtotal,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'bank_charge' => $bankCharge,
                'total_amount' => $totalAmount,
            ];
            
            // Redirect to setoff page
            return redirect()->route('invoices.showSetoff')
                ->with('invoiceData', $invoiceData)
                ->with('invoiceItems', $request->items);
        }
        
        // Create invoice without payments
        try {
            DB::beginTransaction();
            
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'supplier_id' => $request->supplier_id,
                'vehicle_id' => $request->vehicle_id,
                'employee_id' => $request->employee_id,
                'helper_id' => $request->helper_id,
                'subtotal' => $subtotal,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'bank_charge' => $bankCharge,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'status' => 'pending', // New invoices start as pending
            ]);
            
            // Create invoice items and update stock
            foreach ($request->items as $item) {
                // Calculate VAT for this item
                $itemModel = Item::findOrFail($item['item_id']);
                $itemVatAmount = 0;
                if ($itemModel->vat_available) {
                    $itemVatAmount = round(($item['total'] * $vatPercentage) / 100, 2);
                }
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item['item_id'],
                    'tank_id' => $item['tank_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'total' => $item['total'],
                    'vat_amount' => $itemVatAmount,
                ]);
                
                // Increase stock
                $beforeQuantity = $itemModel->stock_quantity;
                $itemModel->increment('stock_quantity', $item['quantity']);
                $afterQuantity = $itemModel->stock_quantity;
                
                // Create stock log
                StockLog::create([
                    'item_id' => $item['item_id'],
                    'tank_id' => $item['tank_id'] ?? null,
                    'type' => 'increase',
                    'before_quantity' => $beforeQuantity,
                    'quantity_change' => $item['quantity'],
                    'after_quantity' => $afterQuantity,
                    'reason' => 'Invoice #' . $invoice->invoice_number,
                    'reference_type' => 'invoice',
                    'reference_id' => $invoice->id,
                    'user_id' => auth()->id(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('invoices.index')
                ->with('success', 'Invoice created successfully without payments.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $invoice->load(['supplier', 'vehicle', 'employee', 'helper', 'items.item', 'paymentSetoffs.payment']);
        
        // If supplier is inactive, redirect with error
        if ($invoice->supplier->status !== 'active') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot view invoices for inactive suppliers.');
        }
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $invoice->load(['supplier', 'items.item']);
        
        // Prevent editing if status is complete
        if ($invoice->status === 'complete') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot edit invoices with complete status.');
        }
        
        // Prevent editing if status is deleted
        if ($invoice->status === 'deleted') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot edit deleted invoices.');
        }
        
        // If supplier is inactive, redirect with error
        if ($invoice->supplier->status !== 'active') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot edit invoices for inactive suppliers.');
        }

        $suppliers = Supplier::where('status', 'active')->get();
        $items = Item::where('status', 'active')->get();
        $tanks = Tank::where('status', 'active')->with('item')->get();
        
        return view('invoices.edit', compact('invoice', 'suppliers', 'items', 'tanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.tank_id' => 'nullable|exists:tanks,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            // Store old values before update
            $oldTotalAmount = $invoice->total_amount;
            $oldSupplierId = $invoice->supplier_id;
            
            // Calculate amounts
            $subtotal = round(collect($request->items)->sum('total'), 2);
            $vatPercentage = $request->vat_percentage ?? 0;
            $vatAmount = round(($subtotal * $vatPercentage) / 100, 2);
            $bankCharge = round($request->bank_charge ?? 0, 2);
            $totalAmount = round($subtotal + $vatAmount + $bankCharge, 2);
            
            // Update invoice
            $invoice->update([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'supplier_id' => $request->supplier_id,
                'subtotal' => $subtotal,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'bank_charge' => $bankCharge,
                'total_amount' => $totalAmount,
            ]);

            // Delete old invoice items and adjust stock
            foreach ($invoice->items as $oldItem) {
                // Decrease stock by old quantity
                $item = Item::find($oldItem->item_id);
                if ($item) {
                    $beforeQuantity = $item->stock_quantity;
                    $item->decrement('stock_quantity', $oldItem->quantity);
                    $afterQuantity = $item->stock_quantity;
                    
                    // Create stock log
                    StockLog::create([
                        'item_id' => $item->id,
                        'tank_id' => $oldItem->tank_id ?? null,
                        'type' => 'decrease',
                        'before_quantity' => $beforeQuantity,
                        'quantity_change' => $oldItem->quantity,
                        'after_quantity' => $afterQuantity,
                        'reason' => 'Invoice #' . $invoice->invoice_number . ' updated',
                        'reference_type' => 'invoice',
                        'reference_id' => $invoice->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
            $invoice->items()->delete();

            // Create new invoice items and adjust stock
            foreach ($request->items as $itemData) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $itemData['item_id'],
                    'tank_id' => $itemData['tank_id'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'cost_price' => $itemData['cost_price'],
                    'total' => $itemData['total'],
                ]);
                
                // Increase stock by new quantity
                $item = Item::find($itemData['item_id']);
                if ($item) {
                    $beforeQuantity = $item->stock_quantity;
                    $item->increment('stock_quantity', $itemData['quantity']);
                    $afterQuantity = $item->stock_quantity;
                    
                    // Create stock log
                    StockLog::create([
                        'item_id' => $item->id,
                        'tank_id' => $itemData['tank_id'] ?? null,
                        'type' => 'increase',
                        'before_quantity' => $beforeQuantity,
                        'quantity_change' => $itemData['quantity'],
                        'after_quantity' => $afterQuantity,
                        'reason' => 'Invoice #' . $request->invoice_number,
                        'reference_type' => 'invoice',
                        'reference_id' => $invoice->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            // Update supplier balance logs if total amount or supplier changed
            if ($oldTotalAmount != $totalAmount || $oldSupplierId != $request->supplier_id) {
                // If supplier changed, update both suppliers' balances
                if ($oldSupplierId != $request->supplier_id) {
                    // Reverse the old supplier's balance
                    $oldSupplier = Supplier::find($oldSupplierId);
                    $oldTotalPayments = round(Payment::where('supplier_id', $oldSupplier->id)->sum('amount'), 2);
                    $oldTotalInvoices = round(Invoice::where('supplier_id', $oldSupplier->id)->sum('total_amount'), 2);
                    $oldPreviousBalance = round($oldTotalPayments - ($oldTotalInvoices - $oldTotalAmount), 2);
                    $oldCurrentBalance = round($oldTotalPayments - $oldTotalInvoices, 2);
                    
                    SupplierBalanceLog::create([
                        'supplier_id' => $oldSupplier->id,
                        'type' => 'invoice',
                        'amount' => -$oldTotalAmount,
                        'previous_balance' => $oldPreviousBalance,
                        'current_balance' => $oldCurrentBalance,
                        'reference_type' => 'App\\Models\\Invoice',
                        'reference_id' => $invoice->id,
                        'description' => 'Invoice #' . $invoice->invoice_number . ' moved to another supplier',
                        'user_id' => auth()->id(),
                    ]);
                }
                
                // Log balance change for current/new supplier
                $supplier = Supplier::find($request->supplier_id);
                $totalPayments = round(Payment::where('supplier_id', $supplier->id)->sum('amount'), 2);
                $totalInvoices = round(Invoice::where('supplier_id', $supplier->id)->sum('total_amount'), 2);
                
                if ($oldSupplierId == $request->supplier_id) {
                    // Same supplier, amount changed
                    $previousBalance = round($totalPayments - ($totalInvoices - $totalAmount + $oldTotalAmount), 2);
                } else {
                    // New supplier
                    $previousBalance = round($totalPayments - ($totalInvoices - $totalAmount), 2);
                }
                
                $currentBalance = $totalPayments - $totalInvoices;
                
                SupplierBalanceLog::create([
                    'supplier_id' => $supplier->id,
                    'type' => 'invoice',
                    'amount' => $totalAmount,
                    'previous_balance' => $previousBalance,
                    'current_balance' => $currentBalance,
                    'reference_type' => 'App\\Models\\Invoice',
                    'reference_id' => $invoice->id,
                    'description' => 'Invoice #' . $invoice->invoice_number . ' updated',
                    'user_id' => auth()->id(),
                ]);
            }

            // Recalculate paid_amount based on payment setoffs
            $paidAmount = round($invoice->paymentSetoffs()->sum('amount'), 2);
            $invoice->update(['paid_amount' => $paidAmount]);
            
            // Auto-complete if remaining is less than 1
            $remainingAmount = round($invoice->total_amount - $paidAmount, 2);
            if ($remainingAmount < 1 && $invoice->status === 'pending') {
                $invoice->update(['status' => 'complete']);
            }

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update invoice: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Toggle invoice status between pending and complete (not used for deleted status)
     */
    public function toggleStatus(Invoice $invoice)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        // Cannot toggle deleted invoices
        if ($invoice->status === 'deleted') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot change status of deleted invoices.');
        }
        
        // Calculate remaining amount
        $paidAmount = round($invoice->paid_amount ?? 0, 2);
        $remainingAmount = round($invoice->total_amount - $paidAmount, 2);
        
        // If remaining is less than 1, automatically set to complete
        if ($remainingAmount < 1) {
            if ($invoice->status !== 'complete') {
                $invoice->update(['status' => 'complete']);
                return redirect()->route('invoices.index')
                    ->with('success', 'Invoice status automatically set to complete due to minimal remaining balance.');
            }
            // If already complete with zero remaining, don't allow changing back to pending
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot change status of completed invoices.');
        }
        
        // Check if invoice has any setoffs (payments applied)
        $hasSetoffs = $invoice->paymentSetoffs()->exists();
        
        if ($hasSetoffs) {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot change status of invoices that have payments applied.');
        }
        
        // Only allow toggle for pending invoices without setoffs
        $newStatus = $invoice->status === 'pending' ? 'complete' : 'pending';
        $invoice->update(['status' => $newStatus]);

        $message = $newStatus === 'pending' 
            ? 'Invoice status changed to pending.' 
            : 'Invoice status changed to complete.';

        return redirect()->route('invoices.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Only allow delete if no payments have been applied (paid_amount = 0)
        $paidAmount = $invoice->paid_amount ?? 0;
        if ($paidAmount > 0) {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot delete invoice that has payments applied.');
        }

        // Soft delete by changing status to 'deleted'
        $invoice->update(['status' => 'deleted']);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Restore a deleted invoice back to pending status.
     */
    public function restore(Invoice $invoice)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Only allow restore if invoice is deleted
        if ($invoice->status !== 'deleted') {
            return redirect()->route('invoices.index')
                ->with('error', 'Only deleted invoices can be restored.');
        }

        // Restore invoice to pending status
        $invoice->update(['status' => 'pending']);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice restored to pending status successfully.');
    }
    
    /**
     * Get item details for AJAX request
     */
    public function getItemDetails($id)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $item = Item::findOrFail($id);
        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'cost_price' => $item->cost_price,
        ]);
    }
    
    /**
     * Display balance logs for a supplier
     */
    public function balanceLogs($supplierId)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $supplier = Supplier::findOrFail($supplierId);
        
        // If supplier is inactive, redirect with error
        if ($supplier->status !== 'active') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot view balance logs for inactive suppliers.');
        }
        
        $logs = SupplierBalanceLog::where('supplier_id', $supplierId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('invoices.balance-logs', compact('supplier', 'logs'));
    }
    
    /**
     * Show the setoff page for invoice payment
     */
    public function showSetoff(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Check if this is for an existing invoice or new invoice
        if ($request->has('invoice_id')) {
            // Existing invoice - load from database
            $invoice = Invoice::with(['supplier', 'items.item', 'paymentSetoffs'])->findOrFail($request->invoice_id);
            
            // If supplier is inactive, redirect with error
            if ($invoice->supplier->status !== 'active') {
                return redirect()->route('invoices.setoffManagement')
                    ->with('error', 'Cannot add payments for invoices with inactive suppliers.');
            }
            
            $invoiceData = [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'invoice_date' => $invoice->invoice_date->format('Y-m-d'),
                'supplier_id' => $invoice->supplier_id,
                'vehicle_id' => $invoice->vehicle_id,
                'employee_id' => $invoice->employee_id,
                'helper_id' => $invoice->helper_id,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'remaining_amount' => $invoice->remaining_amount,
                'is_existing' => true,
            ];
            
            $supplier = $invoice->supplier;
        } else {
            // New invoice - from session
            $invoiceData = session('invoiceData');
            $invoiceItems = session('invoiceItems');
            
            if (!$invoiceData || !$invoiceItems) {
                return redirect()->route('invoices.create')
                    ->with('error', 'Invoice data not found. Please fill the form again.');
            }
            
            $invoiceData['is_existing'] = false;
            $supplier = Supplier::findOrFail($invoiceData['supplier_id']);
        }
        
        // Get all payments for this supplier with remaining balance
        $payments = Payment::where('supplier_id', $supplier->id)
            ->with('setoffs')
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // Check if there are any available payments for this supplier
        if ($payments->isEmpty()) {
            return redirect()->route('payments.index', ['supplier_id' => $supplier->id])
                ->with('error', 'No payments found for supplier "' . $supplier->name . '". Please add a payment first before setting off invoices.')
                ->with('redirect_to_setoff', true)
                ->with('invoice_id', $invoiceData['invoice_id'] ?? null);
        }
        
        // Check if there are any payments with pending status
        $hasAvailablePayments = $payments->filter(function($payment) {
            return $payment->status === 'pending' && $payment->remaining_balance > 0;
        })->isNotEmpty();
        
        if (!$hasAvailablePayments) {
            return redirect()->route('payments.index', ['supplier_id' => $supplier->id])
                ->with('warning', 'All payments for supplier "' . $supplier->name . '" are fully allocated. Please add a new payment to continue.')
                ->with('redirect_to_setoff', true)
                ->with('invoice_id', $invoiceData['invoice_id'] ?? null);
        }
        
        $invoiceItems = $invoiceItems ?? [];
        
        return view('invoices.setoff', compact('invoiceData', 'invoiceItems', 'supplier', 'payments'));
    }
    
    /**
     * Process the setoff and create/update the invoice
     */
    public function processSetoff(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $invoiceData = json_decode($request->invoice_data, true);
        $invoiceItems = json_decode($request->invoice_items, true);
        
        if (!$invoiceData) {
            return redirect()->route('invoices.create')
                ->with('error', 'Invoice data not found. Please fill the form again.');
        }

        $request->validate([
            'selected_payments' => 'required|array|min:1',
            'selected_payments.*' => 'exists:payments,id',
            'payment_amounts' => 'required|array',
            'payment_amounts.*' => 'numeric|min:0.01',
        ]);

        DB::beginTransaction();
        
        try {
            // Check if this is for an existing invoice or new invoice
            if (isset($invoiceData['is_existing']) && $invoiceData['is_existing']) {
                // Existing invoice - just add payments
                $invoice = Invoice::findOrFail($invoiceData['invoice_id']);
                
                // Check if invoice is inactive
                if ($invoice->status === 'inactive') {
                    DB::rollBack();
                    return redirect()->route('invoices.setoffManagement')
                        ->with('error', 'Cannot add payments to inactive invoices.');
                }
                
                $supplier = $invoice->supplier;
                
                $totalSetoffAmount = 0;
                
                foreach ($request->selected_payments as $paymentId) {
                    $setoffAmount = $request->payment_amounts[$paymentId] ?? 0;
                    
                    if ($setoffAmount > 0) {
                        // Create setoff record
                        InvoicePaymentSetoff::create([
                            'invoice_id' => $invoice->id,
                            'payment_id' => $paymentId,
                            'amount' => $setoffAmount,
                        ]);
                        
                        $totalSetoffAmount += $setoffAmount;
                        
                        // Get payment and update remaining_balance
                        $payment = Payment::find($paymentId);
                        $totalSetoffs = round($payment->setoffs()->sum('amount'), 2);
                        $payment->remaining_balance = round($payment->amount - $totalSetoffs, 2);
                        
                        // Auto-complete if remaining balance is less than 1
                        if ($payment->remaining_balance < 1) {
                            $payment->status = 'complete';
                        } else {
                            $payment->status = 'pending';
                        }
                        
                        $payment->save();
                        
                        $reference = $payment->payment_method === 'online' 
                            ? $payment->reference_number 
                            : $payment->cheque_number;
                        
                        // Calculate supplier balance (total payments - total invoices)
                        $totalPayments = round(Payment::where('supplier_id', $supplier->id)->sum('amount'), 2);
                        $totalInvoices = round(Invoice::where('supplier_id', $supplier->id)->sum('total_amount'), 2);
                        $currentBalance = round($totalPayments - $totalInvoices, 2);
                        $previousBalance = round($currentBalance + $setoffAmount, 2);
                        
                        SupplierBalanceLog::create([
                            'supplier_id' => $supplier->id,
                            'type' => 'payment_setoff',
                            'amount' => $setoffAmount,
                            'previous_balance' => $previousBalance,
                            'current_balance' => $currentBalance,
                            'reference_type' => 'App\\Models\\Payment',
                            'reference_id' => $paymentId,
                            'description' => 'Payment ' . $reference . ' (LKR ' . number_format($setoffAmount, 2) . ') set off against Invoice #' . $invoice->invoice_number,
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
                
                // Update invoice paid and remaining amounts
                $invoice->paid_amount = round($invoice->paymentSetoffs()->sum('amount'), 2);
                $invoice->remaining_amount = round($invoice->total_amount - $invoice->paid_amount, 2);
                
                // Auto-inactivate if remaining is zero
                if ($invoice->remaining_amount == 0 && $invoice->status === 'active') {
                    $invoice->status = 'inactive';
                }
                
                $invoice->save();

                DB::commit();
                
                $message = 'Payments added successfully. Total amount: LKR ' . number_format($totalSetoffAmount, 2);
                if ($invoice->remaining_amount > 0) {
                    $message .= '. Remaining balance: LKR ' . number_format($invoice->remaining_amount, 2);
                } else {
                    $message .= '. Invoice fully paid.';
                }
                
                return redirect()->route('invoices.setoffManagement')->with('success', $message);
            } else {
                // New invoice - create invoice and add payments
                if (!$invoiceItems) {
                    return redirect()->route('invoices.create')
                        ->with('error', 'Invoice items not found. Please fill the form again.');
                }
                
                // Create invoice
                $invoice = Invoice::create([
                    'invoice_number' => $invoiceData['invoice_number'],
                    'invoice_date' => $invoiceData['invoice_date'],
                    'supplier_id' => $invoiceData['supplier_id'],
                    'vehicle_id' => $invoiceData['vehicle_id'] ?? null,
                    'employee_id' => $invoiceData['employee_id'] ?? null,
                    'helper_id' => $invoiceData['helper_id'] ?? null,
                    'subtotal' => $invoiceData['subtotal'],
                    'vat_percentage' => $invoiceData['vat_percentage'],
                    'vat_amount' => $invoiceData['vat_amount'],
                    'bank_charge' => $invoiceData['bank_charge'] ?? 0,
                    'total_amount' => $invoiceData['total_amount'],
                ]);
                
                // Save VAT percentage to settings for next invoice
                Setting::set('vat_percentage', $invoiceData['vat_percentage'], 'number');

            // Create invoice items and update stock
            foreach ($invoiceItems as $itemData) {
                // Calculate VAT for this item
                $item = Item::find($itemData['item_id']);
                $itemVatAmount = 0;
                if ($item && $item->vat_available) {
                    $vatPercentage = Setting::get('vat_percentage', 0);
                    $itemVatAmount = round(($itemData['total'] * $vatPercentage) / 100, 2);
                }
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'cost_price' => $itemData['cost_price'],
                    'total' => $itemData['total'],
                    'vat_amount' => $itemVatAmount,
                ]);
                
                // Increase stock quantity for the item
                if ($item) {
                    $beforeQuantity = $item->stock_quantity;
                    $item->increment('stock_quantity', $itemData['quantity']);
                    $afterQuantity = $item->stock_quantity;
                    
                    // Create stock log
                    StockLog::create([
                        'item_id' => $item->id,
                        'type' => 'increase',
                        'before_quantity' => $beforeQuantity,
                        'quantity_change' => $itemData['quantity'],
                        'after_quantity' => $afterQuantity,
                        'reason' => 'Invoice #' . $invoiceData['invoice_number'],
                        'reference_type' => 'invoice',
                        'reference_id' => $invoice->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            // Process payment setoffs
            $totalSetoffAmount = 0;
            $setoffDescriptions = [];
            
            foreach ($request->selected_payments as $paymentId) {
                $setoffAmount = $request->payment_amounts[$paymentId] ?? 0;
                
                if ($setoffAmount > 0) {
                    // Create setoff record
                    InvoicePaymentSetoff::create([
                        'invoice_id' => $invoice->id,
                        'payment_id' => $paymentId,
                        'amount' => $setoffAmount,
                    ]);
                    
                    $totalSetoffAmount += $setoffAmount;
                    
                    // Get payment and update remaining_balance
                    $payment = Payment::find($paymentId);
                    $totalSetoffs = round($payment->setoffs()->sum('amount'), 2);
                    $payment->remaining_balance = round($payment->amount - $totalSetoffs, 2);
                    
                    // Auto-complete if remaining balance is less than 1
                    if ($payment->remaining_balance < 1) {
                        $payment->status = 'complete';
                    } else {
                        $payment->status = 'pending';
                    }
                    
                    $payment->save();
                    
                    $reference = $payment->payment_method === 'online' 
                        ? $payment->reference_number 
                        : $payment->cheque_number;
                    $setoffDescriptions[] = "Payment {$reference} (LKR " . number_format($setoffAmount, 2) . ")";
                }
            }

            // Log balance change for invoice
            $supplier = Supplier::find($invoiceData['supplier_id']);
            $totalPayments = round(Payment::where('supplier_id', $supplier->id)->sum('amount'), 2);
            $totalInvoices = round(Invoice::where('supplier_id', $supplier->id)->sum('total_amount'), 2);
            $previousBalance = round($totalPayments - ($totalInvoices - $invoiceData['total_amount']), 2);
            $currentBalance = round($totalPayments - $totalInvoices, 2);
            
            $setoffInfo = implode(', ', $setoffDescriptions);
            
            SupplierBalanceLog::create([
                'supplier_id' => $supplier->id,
                'type' => 'invoice',
                'amount' => $invoiceData['total_amount'],
                'previous_balance' => $previousBalance,
                'current_balance' => $currentBalance,
                'reference_type' => 'App\\Models\\Invoice',
                'reference_id' => $invoice->id,
                'description' => 'Invoice #' . $invoiceData['invoice_number'] . ' created. Set off against: ' . $setoffInfo,
                'user_id' => auth()->id(),
            ]);
            
            // Log balance changes for each payment setoff
            foreach ($request->selected_payments as $paymentId) {
                $setoffAmount = $request->payment_amounts[$paymentId] ?? 0;
                
                if ($setoffAmount > 0) {
                    $payment = Payment::find($paymentId);
                    $reference = $payment->payment_method === 'online' 
                        ? $payment->reference_number 
                        : $payment->cheque_number;
                    
                    // Recalculate balance after this setoff
                    $totalPayments = round(Payment::where('supplier_id', $supplier->id)->sum('amount'), 2);
                    $totalInvoices = round(Invoice::where('supplier_id', $supplier->id)->sum('total_amount'), 2);
                    $currentBalance = round($totalPayments - $totalInvoices, 2);
                    $previousBalance = round($currentBalance + $setoffAmount, 2);
                    
                    SupplierBalanceLog::create([
                        'supplier_id' => $supplier->id,
                        'type' => 'payment_setoff',
                        'amount' => $setoffAmount,
                        'previous_balance' => $previousBalance,
                        'current_balance' => $currentBalance,
                        'reference_type' => 'App\\Models\\Payment',
                        'reference_id' => $paymentId,
                        'description' => 'Payment ' . $reference . ' (LKR ' . number_format($setoffAmount, 2) . ') set off against Invoice #' . $invoiceData['invoice_number'],
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            DB::commit();
            
            $remainingAmount = $invoiceData['total_amount'] - $totalSetoffAmount;
            $message = 'Invoice created successfully.';
            if ($remainingAmount > 0) {
                $message .= ' Remaining balance: LKR ' . number_format($remainingAmount, 2);
            }
            
            return redirect()->route('invoices.index')->with('success', $message);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()])->withInput();
        }
    }
}
