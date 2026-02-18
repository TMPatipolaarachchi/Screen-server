@extends('layouts.app')

@section('body_class', 'page-dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-5">
        <div class="col-md-8">
            <h2 class="fw-bold mb-2">Hello, {{ auth()->user()->name }}! </h2>
        </div>
    </div>

    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold">
                        Your Profile
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Name:</strong> {{ auth()->user()->name }}
                    </p>
                    <p class="mb-2">
                        <strong>Email:</strong> {{ auth()->user()->email }}
                    </p>
                    @if (auth()->user()->phone)
                        <p class="mb-2">
                            <strong>Phone:</strong> {{ auth()->user()->phone }}
                        </p>
                    @endif
                    @if (auth()->user()->nic_number)
                        <p class="mb-2">
                            <strong>NIC Number:</strong> {{ auth()->user()->nic_number }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold">
                        Account Status
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Role:</strong>
                        <span class="text-capitalize text-muted">
                            {{ auth()->user()->role }}
                        </span>
                    </p>
                    <p class="mb-2">
                        <strong>Account Status:</strong>
                        <span class="text-success fw-bold">Active</span>
                    </p>
                    <p class="mb-2">
                        <strong>Member Since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}
                    </p>
                    <p class="mb-2">
                        <strong>Last Updated:</strong> {{ auth()->user()->updated_at->format('M d, Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

   
</div>
@endsection