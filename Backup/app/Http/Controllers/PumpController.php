<?php

namespace App\Http\Controllers;

use App\Models\Pump;
use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PumpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pumps = Pump::with(['tank', 'meter1', 'meter2'])->latest()->get();
        return view('pumps.index', compact('pumps'));
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

        $tanks = \App\Models\Tank::where('status', 'active')->get();
        $meters = Meter::where('status', 'active')->get();
        return view('pumps.create', compact('tanks', 'meters'));
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

        $request->validate(Pump::createRules());

        Pump::create(array_merge($request->all(), ['status' => 'active']));

        return redirect()->route('pumps.index')->with('success', 'Pump created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pump $pump)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pump->load(['tank', 'meter1', 'meter2']);
        return view('pumps.show', compact('pump'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pump $pump)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $tanks = \App\Models\Tank::where('status', 'active')->get();
        $meters = Meter::where('status', 'active')->get();
        return view('pumps.edit', compact('pump', 'tanks', 'meters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pump $pump)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate(Pump::updateRules($pump->id));

        try {
            $pump->update($request->all());
            return redirect()->route('pumps.index')->with('success', 'Pump updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update pump. The pump name may already exist.')->withInput();
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Pump $pump)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pump->status = $pump->status === 'active' ? 'inactive' : 'active';
        $pump->save();

        return redirect()->back()->with('success', 'Pump status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pump $pump)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pump->delete();

        return redirect()->route('pumps.index')->with('success', 'Pump deleted successfully.');
    }
}
