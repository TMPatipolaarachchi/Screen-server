<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index()
    {
        $employees = Employee::orderBy('name', 'asc')->get();
        
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Employee::rules(), [
            'name.required' => 'Employee name is required.',
            'nic.required' => 'NIC is required.',
            'nic.unique' => 'This NIC already exists.',
            'etf_number.required' => 'ETF number is required.',
            'etf_number.unique' => 'This ETF number already exists.',
            'birthday.required' => 'Birthday is required.',
            'birthday.before' => 'Birthday must be before today.',
            'address.required' => 'Address is required.',
            'role.required' => 'Role is required.',
        ]);

        // Set default status to active if not provided
        $validated['status'] = $validated['status'] ?? 'active';

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate(Employee::rules($employee->id), [
            'name.required' => 'Employee name is required.',
            'nic.required' => 'NIC is required.',
            'nic.unique' => 'This NIC already exists.',
            'etf_number.required' => 'ETF number is required.',
            'etf_number.unique' => 'This ETF number already exists.',
            'birthday.required' => 'Birthday is required.',
            'birthday.before' => 'Birthday must be before today.',
            'address.required' => 'Address is required.',
            'role.required' => 'Role is required.',
            'status.required' => 'Status is required.',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    /**
     * Toggle employee status between active and inactive
     */
    public function toggleStatus(Employee $employee)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $newStatus = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? 'Employee activated successfully.' 
            : 'Employee deactivated successfully.';

        return redirect()->route('employees.index')->with('success', $message);
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}
