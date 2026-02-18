@extends('layouts.app')

@section('body_class', 'page-vehicles')

@section('title', 'Vehicle Management')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-black">Vehicle Management</h2>
            <p class="text-black mb-0">Total Vehicles: <strong>{{ $vehicles->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Vehicle
            </a>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="card">
        <div class="card-body">
            @if ($vehicles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Vehicle Code</th>
                                <th>Vehicle Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicles as $vehicle)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $vehicle->vehicle_code }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $vehicle->vehicle_number }}</strong>
                                    </td>
                                    <td>
                                        <span class="{{ $vehicle->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($vehicle->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('vehicles.toggle-status', $vehicle->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this vehicle's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $vehicle->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $vehicle->status === 'active' ? 'times' : 'check' }} text-black"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted">No vehicles found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No vehicles found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
