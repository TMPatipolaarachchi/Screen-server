@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Manage Users')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">User Management</h2>
            <p class="text-muted mb-0">Total Users: <strong>{{ $users->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>NIC</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>{{ $user->nic_number ?? '-' }}</td>
                                    <td>
                                        <span class="text-capitalize text-muted">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $user->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this user's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $user->status === 'active' ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted">No users found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No users found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection