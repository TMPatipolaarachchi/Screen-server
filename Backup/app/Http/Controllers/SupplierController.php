<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SupplierController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'phone_number' => 'nullable|string|max:20|unique:suppliers,phone_number',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50|unique:suppliers,account_number',
        ]);

        Supplier::create(array_merge($request->all(), ['status' => 'active']));

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'phone_number' => 'nullable|string|max:20|unique:suppliers,phone_number,' . $supplier->id,
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50|unique:suppliers,account_number,' . $supplier->id,
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $supplier->update($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update supplier. The supplier name, phone number, or account number may already exist.')->withInput();
        }
    }

    public function toggleStatus(Supplier $supplier)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $newStatus = $supplier->status === 'active' ? 'inactive' : 'active';
        $supplier->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? 'Supplier activated successfully.' 
            : 'Supplier deactivated successfully.';

        return redirect()->route('suppliers.index')->with('success', $message);
    }
}