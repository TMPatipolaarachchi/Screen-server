<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class BankController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $banks = Bank::all();
        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('banks.create');
    }

    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'account' => 'required|string|max:50|unique:banks,account',
        ]);

        try {
            Bank::create(array_merge($request->all(), ['status' => 'active']));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Unique constraint violation
                return redirect()->back()->withErrors(['name' => 'The name has already been taken.'])->withInput();
            }

            throw $e; // Re-throw other exceptions
        }

        return redirect()->route('banks.index')->with('success', 'Bank created successfully.');
    }

    public function show(Bank $bank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('banks.show', compact('bank'));
    }

    public function edit(Bank $bank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name,' . $bank->id,
            'account' => 'required|string|max:50|unique:banks,account,' . $bank->id,
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $bank->update($request->all());
            return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update bank. The bank name or account number may already exist.')->withInput();
        }
    }

    public function toggleStatus(Bank $bank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $newStatus = $bank->status === 'active' ? 'inactive' : 'active';
        $bank->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? 'Bank activated successfully.' 
            : 'Bank deactivated successfully.';

        return redirect()->route('banks.index')->with('success', $message);
    }
}
