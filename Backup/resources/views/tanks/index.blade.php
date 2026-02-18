@extends('layouts.app')

@section('body_class', 'page-tanks')

@section('title', 'Manage Tanks')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-black">Tank Management</h2>
            <p class="text-black mb-0">Total Tanks: <strong>{{ $tanks->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('tanks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Tank
            </a>
        </div>
    </div>

    <!-- Tanks Table -->
    <div class="card">
        <div class="card-body">
            @if ($tanks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Tank Name</th>
                                <th>Max Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tanks as $tank)
                                <tr class="text-center">
                                    <td>{{ $tank->id }}</td>
                                    <td>
                                        <strong>{{ $tank->item->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $tank->tank_name }}</td>
                                    <td>
                                        <span class="fw-bold">
                                            {{ number_format($tank->max_stock, 2) }} L
                                        </span>
                                    </td>
                                    <td>
                                        @if ($tank->status === 'active')
                                            <span class="text-success fw-bold">Active</span>
                                        @else
                                            <span class="text-warning fw-bold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('tanks.show', $tank) }}" class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('tanks.edit', $tank) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('tanks.toggle-status', $tank) }}" style="display:inline;" data-confirm-message="Are you sure you want to change this tank's status?">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $tank->status === 'active' ? 'danger' : 'success' }}">
                                                    <i class="fas fa-{{ $tank->status === 'active' ? 'times' : 'check' }} text-black"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i> No tanks found in the system.
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No tanks found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection