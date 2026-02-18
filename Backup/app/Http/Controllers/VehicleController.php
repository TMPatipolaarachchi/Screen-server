<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles
     */
    public function index()
    {
        $vehicles = Vehicle::orderBy('vehicle_code', 'asc')->get();
        
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle
     */
    public function create()
    {
        $vehicleCode = Vehicle::generateVehicleCode();
        
        return view('vehicles.create', compact('vehicleCode'));
    }

    /**
     * Store a newly created vehicle
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Vehicle::rules(), [
            'vehicle_number.required' => 'Vehicle number is required.',
            'vehicle_number.unique' => 'This vehicle number already exists.',
        ]);

        // Generate vehicle code
        $validated['vehicle_code'] = Vehicle::generateVehicleCode();
        
        // Set default status to active if not provided
        $validated['status'] = $validated['status'] ?? 'active';

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully!');
    }

    /**
     * Display the specified vehicle
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle
     */
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate(Vehicle::rules($vehicle->id), [
            'vehicle_number.required' => 'Vehicle number is required.',
            'vehicle_number.unique' => 'This vehicle number already exists.',
            'status.required' => 'Status is required.',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully!');
    }

    /**
     * Toggle vehicle status between active and inactive
     */
    public function toggleStatus(Vehicle $vehicle)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $newStatus = $vehicle->status === 'active' ? 'inactive' : 'active';
        $vehicle->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? 'Vehicle activated successfully.' 
            : 'Vehicle deactivated successfully.';

        return redirect()->route('vehicles.index')->with('success', $message);
    }

    /**
     * Remove the specified vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        
        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully!');
    }
}
