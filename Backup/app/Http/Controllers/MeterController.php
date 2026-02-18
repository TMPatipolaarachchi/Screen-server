<?php

namespace App\Http\Controllers;

use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeterController extends Controller
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

        $meters = Meter::latest()
            ->leftJoin('sales', function($join) {
                $join->on('meters.id', '=', 'sales.meter_id')
                     ->where('sales.status', '=', 'complete')
                     ->whereNotNull('sales.completion_meter_value')
                     ->whereRaw('sales.id = (SELECT id FROM sales WHERE meter_id = meters.id AND status = "complete" AND completion_meter_value IS NOT NULL ORDER BY created_at DESC LIMIT 1)');
            })
            ->select('meters.*', 'sales.completion_meter_value as latest_meter_value')
            ->get();
            
        return view('meters.index', compact('meters'));
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

        $items = \App\Models\Item::where('status', 'active')->orderBy('name')->get();
        return view('meters.create', compact('items'));
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

        $request->validate(Meter::createRules());

        Meter::create(array_merge($request->all(), ['status' => 'active']));

        return redirect()->route('meters.index')->with('success', 'Meter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meter $meter)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('meters.show', compact('meter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meter $meter)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $items = \App\Models\Item::where('status', 'active')->orderBy('name')->get();
        return view('meters.edit', compact('meter', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meter $meter)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate(Meter::updateRules($meter->id));

        try {
            $meter->update($request->all());
            return redirect()->route('meters.index')->with('success', 'Meter updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update meter. The meter name may already exist.')->withInput();
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Meter $meter)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $meter->status = $meter->status === 'active' ? 'inactive' : 'active';
        $meter->save();

        return redirect()->back()->with('success', 'Meter status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meter $meter)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $meter->delete();

        return redirect()->route('meters.index')->with('success', 'Meter deleted successfully.');
    }
}
