@extends('layouts.app')

@section('body_class', 'page-suppliers')

@section('title', 'Manage Suppliers')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Supplier Management</h2>
            <p class="text-muted mb-0">Total Suppliers: <strong>{{ $suppliers->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Supplier
            </a>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card">
        <div class="card-body">
            @if ($suppliers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $supplier)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                    </td>
                                    <td>{{ $supplier->phone_number ?? '-' }}</td>
                                    <td>{{ $supplier->bank_name ?? '-' }}</td>
                                    <td>{{ $supplier->account_number ?? '-' }}</td>
                                    <td>
                                        <span class="{{ $supplier->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($supplier->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('suppliers.toggle-status', $supplier->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this supplier's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $supplier->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $supplier->status === 'active' ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted">No suppliers found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No suppliers found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection