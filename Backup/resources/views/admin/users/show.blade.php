@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'User Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        User Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Full Name:</strong><br>
                                {{ $user->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Role:</strong><br>
                                <span class="text-capitalize text-muted">
                                    {{ $user->role }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Email:</strong><br>
                                {{ $user->email }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Phone:</strong><br>
                                {{ $user->phone ?? 'Not provided' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>NIC Number:</strong><br>
                                {{ $user->nic_number ?? 'Not provided' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <span class="{{ $user->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Member Since:</strong><br>
                                {{ $user->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $user->updated_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>
                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection