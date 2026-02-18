@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Manage Customers')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Customer Management</h2>
            <p class="text-muted mb-0">Total Customers: <strong>{{ $customers->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Customer
            </a>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body">
            @if ($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                    </td>
                                    <td>{{ $customer->phone_number }}</td>
                                    <td>{{ Str::limit($customer->address, 50) }}</td>
                                    <td>
                                        <span class="{{ $customer->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('customers.toggle-status', $customer->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this customer's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $customer->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $customer->status === 'active' ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted">No customers found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No customers found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
