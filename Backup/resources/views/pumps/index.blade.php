@extends('layouts.app')

@section('body_class', 'page-pumps')

@section('title', 'Manage Pumps')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-black">Pump Management</h2>
            <p class="text-black mb-0">Total Pumps: <strong>{{ $pumps->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('pumps.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Pump
            </a>
        </div>
    </div>

    <!-- Pumps Table -->
    <div class="card">
        <div class="card-body">
            @if ($pumps->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Tank</th>
                                <th>Meter 1</th>
                                <th>Meter 2</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pumps as $pump)
                                <tr class="text-center">
                                    <td>{{ $pump->id }}</td>
                                    <td>
                                        <strong>{{ $pump->name }}</strong>
                                    </td>
                                    <td>{{ $pump->tank->tank_name ?? 'N/A' }}</td>
                                    <td>{{ $pump->meter1->meter_name ?? 'N/A' }}</td>
                                    <td>{{ $pump->meter2->meter_name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($pump->status === 'active')
                                            <span class="text-success fw-bold">Active</span>
                                        @else
                                            <span class="text-warning fw-bold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('pumps.show', $pump) }}" class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pumps.edit', $pump) }}" class="btn btn-sm btn-outline-primary me-1"title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('pumps.toggle-status', $pump) }}" style="display:inline;" data-confirm-message="Are you sure you want to change this pump's status?">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $pump->status === 'active' ? 'danger' : 'success' }}" title="Toggle Status">
                                                    <i class="fas fa-{{ $pump->status === 'active' ? 'times' : 'check' }} text-black"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-info-circle"></i> No pumps found in the system.
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
               <div class="alert alert-info" role="alert">
               <i class="fas fa-info-circle"></i> No pumps found in the system.
               </div>
            @endif
        </div>
    </div>
</div>
@endsection