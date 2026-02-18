@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Employee Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Employee Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Name:</strong><br>
                                {{ $employee->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>NIC:</strong><br>
                                {{ $employee->nic }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>ETF Number:</strong><br>
                                {{ $employee->etf_number }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Birthday:</strong><br>
                                {{ $employee->birthday->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="mb-2">
                                <strong>Address:</strong><br>
                                {{ $employee->address }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Role:</strong><br>
                                <span class="text-capitalize">{{ $employee->role }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <span class="{{ $employee->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created Date:</strong><br>
                                {{ $employee->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $employee->updated_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>
                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
