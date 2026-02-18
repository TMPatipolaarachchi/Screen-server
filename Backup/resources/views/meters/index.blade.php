@extends('layouts.app')

@section('body_class', 'page-meters')

@section('title', 'Manage Meters')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-black">Meter Management</h2>
            <p class="text-black mb-0">Total Meters: <strong>{{ $meters->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('meters.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Meter
            </a>
        </div>
    </div>

    <!-- Meters Table -->
    <div class="card">
        <div class="card-body">
            @if ($meters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Meter Name</th>
                                <th>Item Name</th>
                                <th>Meter Value</th>
                                <th>Status</th>
                                <th >Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($meters as $meter)
                                <tr class="text-center">
                                    <td>{{ $meter->id }}</td>
                                    <td>
                                        <strong>{{ $meter->meter_name }}</strong>
                                    </td>
                                    <td>
                                        @if($meter->item)
                                            {{ $meter->item->name }}
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($meter->meter_value)
                                            {{ number_format($meter->meter_value, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($meter->status === 'active')
                                            <span class="text-success fw-bold">Active</span>
                                        @else
                                            <span class="text-warning fw-bold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('meters.show', $meter) }}" class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('meters.edit', $meter) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('meters.toggle-status', $meter) }}" style="display:inline;" data-confirm-message="Are you sure you want to change this meter's status?">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $meter->status === 'active' ? 'danger' : 'success' }}" title="Toggle Status">
                                                    <i class="fas fa-{{ $meter->status === 'active' ? 'times' : 'check' }} text-black"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-info-circle"></i> No meters found in the system.
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> No meters found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection