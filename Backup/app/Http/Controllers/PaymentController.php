<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Supplier;
use App\Models\Bank;
use App\Models\Invoice;
use App\Models\SupplierBalanceLog;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments with form
     */
    public function index(Request $request)
    {
        // Get status filter from request
        $statusFilter = $request->get('status', 'pending');
        
        // Get date filter from request
        $month = $request->get('month');
        $year = $request->get('year');
        
        // Date filter from request
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Supplier filter from request
        $supplierId = $request->get('supplier_id');

        $query = Payment::with(['supplier', 'bank'])
            ->whereHas('supplier', function($query) {
                $query->where('status', 'active');
            });

        // Apply supplier filter if provided
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($startDate || $endDate) {
            // Date filter: show all payments in range, then apply status filter if set
            if ($startDate) {
                $query->whereDate('payment_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('payment_date', '<=', $endDate);
            }

            if ($statusFilter === 'deleted') {
                $query->where('status', 'deleted');
            } elseif ($statusFilter === 'complete') {
                $query->where('status', 'complete');
            } elseif ($statusFilter === 'pending') {
                $query->where('status', 'pending');
            }
        } else {
            // No date filter: apply status-specific logic
            if ($statusFilter === 'pending') {
                // Show all pending payments regardless of date
                $query->where('status', 'pending');
            } elseif ($statusFilter === 'complete') {
                // Show only current month completed payments
                $query->where('status', 'complete')
                      ->whereMonth('payment_date', now()->month)
                      ->whereYear('payment_date', now()->year);
            } elseif ($statusFilter === 'deleted') {
                // Show only current month deleted payments
                $query->where('status', 'deleted')
                      ->whereMonth('payment_date', now()->month)
                      ->whereYear('payment_date', now()->year);
            } else {
                // Default behavior: show all pending + current month completed/deleted
                $query->where(function($q) {
                    $q->where('status', 'pending'); // pending, any date
                })
                ->orWhere(function($q) {
                    $q->where('status', 'complete')
                      ->whereMonth('payment_date', now()->month)
                      ->whereYear('payment_date', now()->year);
                })
                ->orWhere(function($q) {
                    $q->where('status', 'deleted')
                      ->whereMonth('payment_date', now()->month)
                      ->whereYear('payment_date', now()->year);
                });
            }
        }

        $payments = $query->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Build base query for counts with date filtering
        $baseQuery = Payment::whereHas('supplier', function($query) {
                $query->where('status', 'active');
            });
        
        // Apply date filter to counts - default to current month and year if no explicit filter
        // if (!$request->has('month') && !$request->has('year')) {
        //     $baseQuery->whereMonth('payment_date', now()->month)
        //               ->whereYear('payment_date', now()->year);
        // } elseif ($month || $year) {
        //     if ($month) {
        //         $baseQuery->whereMonth('payment_date', $month);
        //     }
        //     if ($year) {
        //         $baseQuery->whereYear('payment_date', $year);
        //     }
        // }
        
        // Get counts for filter buttons
        $completeCount = $baseQuery->clone()->where('status', 'complete')->count();
        $pendingCount = $baseQuery->clone()->where('status', 'pending')->count();
        
        // For deleted count, apply same date filtering
        $deletedBaseQuery = Payment::where('status', 'deleted')
            ->whereHas('supplier', function($query) {
                $query->where('status', 'active');
            });
        
        // Apply date filter to deleted counts
        if (!$request->has('month') && !$request->has('year')) {
            $deletedBaseQuery->whereMonth('payment_date', now()->month)
                             ->whereYear('payment_date', now()->year);
        } elseif ($month || $year) {
            if ($month) {
                $deletedBaseQuery->whereMonth('payment_date', $month);
            }
            if ($year) {
                $deletedBaseQuery->whereYear('payment_date', $year);
            }
        }
        
        $deletedCount = $deletedBaseQuery->count();
        
        $suppliers = Supplier::active()->orderBy('name')->get();
        $banks = Bank::active()->orderBy('name')->get();
        $invoices = Invoice::with('supplier')
            ->whereHas('supplier', function($query) {
                $query->where('status', 'active');
            })
            ->get();
        
        return view('payments.index', compact('payments', 'suppliers', 'banks', 'invoices', 'statusFilter', 'completeCount', 'pendingCount', 'deletedCount'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $rules = Payment::rules();
        
        // Add uniqueness validation for online payments
        if ($request->payment_method === 'online') {
            $rules['reference_number'][] = 'unique:payments,reference_number,NULL,id,bank_id,' . $request->bank_id;
        }
        
        // Add uniqueness validation for cheque payments
        if ($request->payment_method === 'cheque') {
            $rules['cheque_number'][] = 'unique:payments,cheque_number';
        }
        
        $validated = $request->validate($rules, [
            'payment_date.required' => 'Payment date is required.',
            'payment_method.required' => 'Payment method is required.',
            'supplier_id.required' => 'Please select a supplier.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be greater than 0.',
            'bank_id.required_if' => 'Bank account is required for online payment.',
            'reference_number.required_if' => 'Reference number is required for online payment.',
            'reference_number.unique' => 'This reference number already exists for the selected bank account.',
            'cheque_number.required_if' => 'Cheque number is required for cheque payment.',
            'cheque_number.unique' => 'This cheque number already exists.',
        ]);

        // Clean up data based on payment method
        if ($validated['payment_method'] === 'online') {
            $validated['cheque_number'] = null;
        } else {
            $validated['bank_id'] = null;
            $validated['reference_number'] = null;
        }

        // Set initial remaining_balance to the payment amount and status as pending
        $validated['remaining_balance'] = $validated['amount'];
        $validated['status'] = 'pending';

        $payment = Payment::create($validated);

        // Log balance change
        $supplier = Supplier::find($validated['supplier_id']);
        $totalPayments = Payment::where('supplier_id', $supplier->id)->sum('amount');
        $totalInvoices = Invoice::where('supplier_id', $supplier->id)->sum('total_amount');
        $previousBalance = ($totalPayments - $validated['amount']) - $totalInvoices;
        $currentBalance = $totalPayments - $totalInvoices;
        
        SupplierBalanceLog::create([
            'supplier_id' => $supplier->id,
            'type' => 'payment',
            'amount' => $validated['amount'],
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance,
            'reference_type' => 'App\\Models\\Payment',
            'reference_id' => $payment->id,
            'description' => 'Payment recorded via ' . ucfirst($validated['payment_method']),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load(['supplier', 'bank', 'setoffs.invoice']);
        
        // If supplier is inactive, redirect with error
        if ($payment->supplier->status !== 'active') {
            return redirect()->route('payments.index')
                ->with('error', 'Cannot view payments for inactive suppliers.');
        }
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(Payment $payment)
    {
        $payment->load('supplier');
        
        // Prevent editing if payment is not pending
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.index')
                ->with('error', 'Cannot edit payments that are not pending.');
        }
        
        // If supplier is inactive, redirect with error
        if ($payment->supplier->status !== 'active') {
            return redirect()->route('payments.index')
                ->with('error', 'Cannot edit payments for inactive suppliers.');
        }
        
        $suppliers = Supplier::active()->orderBy('name')->get();
        $banks = Bank::active()->orderBy('name')->get();
        
        return view('payments.edit', compact('payment', 'suppliers', 'banks'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, Payment $payment)
    {
        $rules = Payment::rules($payment->id);
        
        // Add uniqueness validation for online payments (excluding current payment)
        if ($request->payment_method === 'online') {
            $rules['reference_number'][] = 'unique:payments,reference_number,' . $payment->id . ',id,bank_id,' . $request->bank_id;
        }
        
        // Add uniqueness validation for cheque payments (excluding current payment)
        if ($request->payment_method === 'cheque') {
            $rules['cheque_number'][] = 'unique:payments,cheque_number,' . $payment->id;
        }
        
        $validated = $request->validate($rules, [
            'payment_date.required' => 'Payment date is required.',
            'payment_method.required' => 'Payment method is required.',
            'supplier_id.required' => 'Please select a supplier.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be greater than 0.',
            'bank_id.required_if' => 'Bank account is required for online payment.',
            'reference_number.required_if' => 'Reference number is required for online payment.',
            'reference_number.unique' => 'This reference number already exists for the selected bank account.',
            'cheque_number.required_if' => 'Cheque number is required for cheque payment.',
            'cheque_number.unique' => 'This cheque number already exists.',
        ]);

        // Clean up data based on payment method
        if ($validated['payment_method'] === 'online') {
            $validated['cheque_number'] = null;
        } else {
            $validated['bank_id'] = null;
            $validated['reference_number'] = null;
        }

        // Store old values before update
        $oldAmount = $payment->amount;
        $oldSupplierId = $payment->supplier_id;

        // Calculate used amount from setoffs (with rounding for precision)
        $usedAmount = round($payment->setoffs()->sum('amount'), 2);
        
        // Calculate new remaining balance
        $validated['remaining_balance'] = round($validated['amount'] - $usedAmount, 2);
        
        // Update status based on remaining balance
        if ($validated['remaining_balance'] < 1) {
            $validated['status'] = 'complete';
        } else {
            $validated['status'] = 'pending';
        }
        
        // Check if new amount is less than used amount
        if ($validated['amount'] < $usedAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment amount cannot be less than the already used amount (LKR ' . number_format($usedAmount, 2) . ')');
        }

        $payment->update($validated);

        // Update supplier balance logs if amount or supplier changed
        if ($oldAmount != $validated['amount'] || $oldSupplierId != $validated['supplier_id']) {
            // If supplier changed, update both suppliers' balances
            if ($oldSupplierId != $validated['supplier_id']) {
                // Reverse the old supplier's balance
                $oldSupplier = Supplier::find($oldSupplierId);
                $oldTotalPayments = Payment::where('supplier_id', $oldSupplier->id)->sum('amount');
                $oldTotalInvoices = Invoice::where('supplier_id', $oldSupplier->id)->sum('total_amount');
                $oldPreviousBalance = ($oldTotalPayments + $oldAmount) - $oldTotalInvoices;
                $oldCurrentBalance = $oldTotalPayments - $oldTotalInvoices;
                
                SupplierBalanceLog::create([
                    'supplier_id' => $oldSupplier->id,
                    'type' => 'payment',
                    'amount' => -$oldAmount,
                    'previous_balance' => $oldPreviousBalance,
                    'current_balance' => $oldCurrentBalance,
                    'reference_type' => 'App\\Models\\Payment',
                    'reference_id' => $payment->id,
                    'description' => 'Payment moved to another supplier',
                    'user_id' => auth()->id(),
                ]);
            }
            
            // Log balance change for current/new supplier
            $supplier = Supplier::find($validated['supplier_id']);
            $totalPayments = Payment::where('supplier_id', $supplier->id)->sum('amount');
            $totalInvoices = Invoice::where('supplier_id', $supplier->id)->sum('total_amount');
            
            if ($oldSupplierId == $validated['supplier_id']) {
                // Same supplier, amount changed
                $previousBalance = ($totalPayments - $validated['amount'] + $oldAmount) - $totalInvoices;
            } else {
                // New supplier
                $previousBalance = ($totalPayments - $validated['amount']) - $totalInvoices;
            }
            
            $currentBalance = $totalPayments - $totalInvoices;
            
            SupplierBalanceLog::create([
                'supplier_id' => $supplier->id,
                'type' => 'payment',
                'amount' => $validated['amount'],
                'previous_balance' => $previousBalance,
                'current_balance' => $currentBalance,
                'reference_type' => 'App\\Models\\Payment',
                'reference_id' => $payment->id,
                'description' => 'Payment updated via ' . ucfirst($validated['payment_method']),
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Soft delete payment (hide from web app)
     */
    public function toggleStatus(Payment $payment)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        // Check if payment can be deleted
        if (!$payment->canDelete()) {
            return redirect()->route('payments.index')
                ->with('error', 'Cannot delete payments that have been used in setoffs.');
        }
        
        // Change payment status to deleted
        $payment->update(['status' => 'deleted']);

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Restore a soft deleted payment
     */
    public function restore($id)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $payment = Payment::where('status', 'deleted')->findOrFail($id);
        
        // Determine correct status based on remaining balance
        $newStatus = $payment->remaining_balance < 1 ? 'complete' : 'pending';
        $payment->update(['status' => $newStatus]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment restored successfully.');
    }

    /**
     * Remove the specified payment
     */
    public function destroy(Payment $payment)
    {
        // Get supplier info before deleting
        $supplier = $payment->supplier;
        $amount = $payment->amount;
        $paymentMethod = $payment->payment_method;
        
        // Calculate balances
        $totalPayments = Payment::where('supplier_id', $supplier->id)->sum('amount');
        $totalInvoices = Invoice::where('supplier_id', $supplier->id)->sum('total_amount');
        $previousBalance = $totalPayments - $totalInvoices;
        $currentBalance = ($totalPayments - $amount) - $totalInvoices;
        
        // Log balance change before deletion
        SupplierBalanceLog::create([
            'supplier_id' => $supplier->id,
            'type' => 'payment_deleted',
            'amount' => $amount,
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance,
            'reference_type' => 'App\\Models\\Payment',
            'reference_id' => $payment->id,
            'description' => 'Payment of Rs. ' . number_format($amount, 2) . ' (' . ucfirst($paymentMethod) . ') deleted',
            'user_id' => auth()->id(),
        ]);
        
        // Update status to deleted instead of permanent deletion
        $payment->update(['status' => 'deleted']);
        
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully!');
    }
    
    /**
     * Display balance logs for a supplier
     */
    public function balanceLogs($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        // If supplier is inactive, redirect with error
        if ($supplier->status !== 'active') {
            return redirect()->route('payments.index')
                ->with('error', 'Cannot view balance logs for inactive suppliers.');
        }
        
        $logs = SupplierBalanceLog::where('supplier_id', $supplierId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('payments.balance-logs', compact('supplier', 'logs'));
    }
}
