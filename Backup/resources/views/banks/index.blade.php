@extends('layouts.app')

@section('body_class', 'page-banks')

@section('title', 'Manage Banks')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Bank Management</h2>
            <p class="text-muted mb-0">Total Banks: <strong>{{ $banks->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('banks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Bank
            </a>
        </div>
    </div>

    <!-- Banks Table -->
    <div class="card">
        <div class="card-body">
            @if ($banks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Account</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banks as $bank)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $bank->name }}</strong>
                                    </td>
                                    <td>{{ $bank->account }}</td>
                                    <td>
                                        <span class="{{ $bank->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($bank->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('banks.show', $bank->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('banks.edit', $bank->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('banks.toggle-status', $bank->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this bank's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $bank->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $bank->status === 'active' ? 'times' : 'check' }}" style="color: black;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted">No banks found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No banks found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
