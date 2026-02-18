<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Meter;
use App\Models\Pump;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display the create sale form
     */
    public function create()
    {
        $meters = Meter::orderBy('meter_name', 'asc')->get();
        $pumps = Pump::orderBy('name', 'asc')->get();
        $pumpers = Employee::where('status', 'active')
                           ->where('role', 'pumper')
                           ->orderBy('name', 'asc')
                           ->get();
        
        return view('sales.create', compact('meters', 'pumps', 'pumpers'));
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Sale::rules(), [
            'meter_id.required' => 'Meter is required.',
            'meter_id.exists' => 'Selected meter does not exist.',
            'pump_id.required' => 'Pump is required.',
            'pump_id.exists' => 'Selected pump does not exist.',
            'employee_id.required' => 'Pumper is required.',
            'employee_id.exists' => 'Selected pumper does not exist.',
            'last_meter_value.required' => 'Last meter value is required.',
            'last_meter_value.numeric' => 'Last meter value must be a number.',
            'last_meter_value.min' => 'Last meter value must be at least 0.',
        ]);

        // Set default status to pending if not provided
        $validated['status'] = $validated['status'] ?? 'pending';

        Sale::create($validated);

        return redirect()->route('sales.status')
            ->with('success', 'Sale record created successfully!');
    }

    /**
     * Display the sales status/reports page
     */
    public function status()
    {
        $sales = Sale::with(['meter.item', 'pump', 'employee', 'customer', 'saleCustomers.customer'])
                     ->orderBy('created_at', 'desc')
                     ->get();
        
        // Calculate total selling price for all completed sales from database
        $totalSellingPrice = $sales->where('status', 'complete')->sum('total_selling_price');

        // Calculate total sales (sum of all customer prices)
        $totalSales = 0;
        foreach ($sales->where('status', 'complete') as $sale) {
            $totalSales += $sale->saleCustomers->sum('price');
        }

        // Get customer-wise breakdown with payment method details
        $customerBreakdown = \DB::table('sale_customers')
            ->join('customers', 'sale_customers.customer_id', '=', 'customers.id')
            ->join('sales', 'sale_customers.sale_id', '=', 'sales.id')
            ->where('sales.status', 'complete')
            ->select(
                'customers.id as customer_id',
                'customers.name', 
                'customers.phone_number', 
                \DB::raw('SUM(sale_customers.quantity) as total_quantity'), 
                \DB::raw('SUM(sale_customers.price) as total_price'),
                \DB::raw('GROUP_CONCAT(DISTINCT sale_customers.payment_method) as payment_methods')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone_number')
            ->orderBy('total_price', 'desc')
            ->get();
        
        return view('sales.status', compact('sales', 'totalSellingPrice', 'totalSales', 'customerBreakdown'));
    }

    /**
     * Update the status of a sale
     */
    public function updateStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'status' => 'required|in:pending,complete',
        ]);

        $sale->update([
            'status' => $request->status,
        ]);

        return redirect()->route('sales.status')
            ->with('success', 'Sale status updated successfully!');
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        $sale->load(['meter.item', 'pump', 'employee', 'customer', 'saleCustomers.customer']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the completion form for a sale
     */
    public function complete(Sale $sale)
    {
        // Only allow completion for pending sales
        if ($sale->status !== 'pending') {
            return redirect()->route('sales.status')
                ->with('error', 'This sale has already been completed.');
        }

        $customers = Customer::where('status', 'active')->orderBy('name', 'asc')->get();
        $sale->load(['meter.item', 'pump', 'employee']);
        
        return view('sales.complete', compact('sale', 'customers'));
    }

    /**
     * Process the completion of a sale
     */
    public function processComplete(Request $request, Sale $sale)
    {
        // Only allow completion for pending sales
        if ($sale->status !== 'pending') {
            return redirect()->route('sales.status')
                ->with('error', 'This sale has already been completed.');
        }

        $validated = $request->validate([
            'completion_meter_value' => 'required|numeric|min:0',
            'customers' => 'required|array|min:1',
            'customers.*.customer_id' => 'required|exists:customers,id',
            'customers.*.quantity' => 'required|numeric|min:0.01',
            'customers.*.price' => 'required|numeric|min:0',
            'customers.*.payment_method' => 'required|in:cash,cheque,online',
        ], [
            'completion_meter_value.required' => 'Completion meter value is required.',
            'completion_meter_value.numeric' => 'Completion meter value must be a number.',
            'completion_meter_value.min' => 'Completion meter value must be at least 0.',
            'customers.required' => 'At least one customer is required.',
            'customers.min' => 'At least one customer is required.',
            'customers.*.customer_id.required' => 'Customer is required.',
            'customers.*.customer_id.exists' => 'Selected customer does not exist.',
            'customers.*.quantity.required' => 'Quantity is required.',
            'customers.*.quantity.numeric' => 'Quantity must be a number.',
            'customers.*.quantity.min' => 'Quantity must be at least 0.01.',
            'customers.*.price.required' => 'Price is required.',
            'customers.*.price.numeric' => 'Price must be a number.',
            'customers.*.payment_method.required' => 'Payment method is required.',
            'customers.*.payment_method.in' => 'Payment method must be cash, cheque, or online.',
        ]);

        // Calculate total selling price
        $quantity = $validated['completion_meter_value'] - $sale->last_meter_value;
        $sellingPrice = $sale->meter->item->selling_price ?? 0;
        $totalSellingPrice = $quantity * $sellingPrice;

        // Update the sale with completion data
        $sale->update([
            'completion_meter_value' => $validated['completion_meter_value'],
            'total_selling_price' => $totalSellingPrice,
            'status' => 'complete',
        ]);

        // Update the meter's meter_value with the completion_meter_value
        $sale->meter->update([
            'meter_value' => $validated['completion_meter_value'],
        ]);

        // Store customer sales data in sale_customers table
        if (!empty($validated['customers'])) {
            foreach ($validated['customers'] as $customerData) {
                $sale->saleCustomers()->create([
                    'customer_id' => $customerData['customer_id'],
                    'quantity' => $customerData['quantity'],
                    'price' => $customerData['price'],
                    'payment_method' => $customerData['payment_method'],
                ]);
            }

            // Store the first customer's ID in the sale record for backward compatibility
            $firstCustomer = reset($validated['customers']);
            $sale->update(['customer_id' => $firstCustomer['customer_id']]);
        }

        return redirect()->route('sales.status')
            ->with('success', 'Sale completed successfully!');
    }

    /**
     * Get the last meter value for a specific meter
     */
    public function getLastMeterValue($meterId)
    {
        $meter = \App\Models\Meter::find($meterId);

        if ($meter && $meter->meter_value) {
            return response()->json([
                'has_previous' => true,
                'last_value' => $meter->meter_value,
                'meter_id' => $meter->id
            ]);
        }

        return response()->json([
            'has_previous' => false,
            'last_value' => null
        ]);
    }

    /**
     * Display all sales for a specific customer
     */
    public function customerSales(Customer $customer)
    {
        // Get all sales where this customer made purchases
        $salesData = \DB::table('sale_customers')
            ->join('sales', 'sale_customers.sale_id', '=', 'sales.id')
            ->join('meters', 'sales.meter_id', '=', 'meters.id')
            ->join('items', 'meters.item_id', '=', 'items.id')
            ->join('pumps', 'sales.pump_id', '=', 'pumps.id')
            ->join('employees', 'sales.employee_id', '=', 'employees.id')
            ->where('sale_customers.customer_id', $customer->id)
            ->where('sales.status', 'complete')
            ->select(
                'sales.id as sale_id',
                'sales.created_at',
                'sales.updated_at',
                'sales.last_meter_value',
                'sales.completion_meter_value',
                'sales.total_selling_price',
                'meters.meter_name',
                'items.name as item_name',
                'items.selling_price',
                'pumps.name as pump_name',
                'employees.name as employee_name',
                'sale_customers.quantity',
                'sale_customers.price',
                'sale_customers.payment_method'
            )
            ->orderBy('sales.created_at', 'desc')
            ->get();

        // Calculate summary statistics
        $totalQuantity = $salesData->sum('quantity');
        $totalAmount = $salesData->sum('price');
        $totalSales = $salesData->count();

        // Group payment methods
        $paymentMethodBreakdown = $salesData->groupBy('payment_method')->map(function ($items, $method) {
            return [
                'method' => $method,
                'count' => $items->count(),
                'total' => $items->sum('price')
            ];
        });

        return view('sales.customer', compact('customer', 'salesData', 'totalQuantity', 'totalAmount', 'totalSales', 'paymentMethodBreakdown'));
    }}