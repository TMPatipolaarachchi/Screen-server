<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index()
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Customer::rules(), [
            'name.required' => 'Customer name is required.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.unique' => 'This phone number already exists.',
            'address.required' => 'Address is required.',
        ]);

        // Set default status to active if not provided
        $validated['status'] = $validated['status'] ?? 'active';

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate(Customer::rules($customer->id), [
            'name.required' => 'Customer name is required.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.unique' => 'This phone number already exists.',
            'address.required' => 'Address is required.',
            'status.required' => 'Status is required.',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Toggle customer status between active and inactive
     */
    public function toggleStatus(Customer $customer)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Toggle status
        $newStatus = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->update(['status' => $newStatus]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer status updated successfully!');
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy(Customer $customer)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}
