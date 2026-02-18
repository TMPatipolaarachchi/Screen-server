<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TankController extends Controller
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

        $tanks = Tank::with(['item'])->latest()->get();
        return view('tanks.index', compact('tanks'));
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

        $items = Item::where('status', 'active')->get();
        return view('tanks.create', compact('items'));
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

        $request->validate(Tank::createRules());

        Tank::create(array_merge($request->all(), ['status' => 'active']));

        return redirect()->route('tanks.index')->with('success', 'Tank created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tank $tank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $tank->load(['item']);
        return view('tanks.show', compact('tank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tank $tank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $items = Item::where('status', 'active')->get();
        return view('tanks.edit', compact('tank', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tank $tank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate(Tank::updateRules($tank->id));

        try {
            $tank->update($request->all());
            return redirect()->route('tanks.index')->with('success', 'Tank updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update tank. The tank name may already exist.')->withInput();
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Tank $tank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $tank->status = $tank->status === 'active' ? 'inactive' : 'active';
        $tank->save();

        return redirect()->back()->with('success', 'Tank status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tank $tank)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $tank->delete();

        return redirect()->route('tanks.index')->with('success', 'Tank deleted successfully.');
    }
}
