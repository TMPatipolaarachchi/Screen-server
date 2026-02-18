@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Manage Employees')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Employee Management</h2>
            <p class="text-muted mb-0">Total Employees: <strong>{{ $employees->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Employee
            </a>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-body">
            @if ($employees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>NIC</th>
                                <th>ETF Number</th>
                                <th>Birthday</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $employee->name }}</strong>
                                    </td>
                                    <td>{{ $employee->nic }}</td>
                                    <td>{{ $employee->etf_number }}</td>
                                    <td>{{ $employee->birthday->format('M d, Y') }}</td>
                                    <td>
                                        <span class="text-capitalize text-muted">
                                            {{ $employee->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $employee->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('employees.toggle-status', $employee->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this employee's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $employee->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $employee->status === 'active' ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted">No employees found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                 <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No employees found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
