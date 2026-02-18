@extends('layouts.app')

@section('body_class', 'page-vehicles')

@section('title', 'Vehicle Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Vehicle Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Vehicle Code:</strong><br>
                                {{ $vehicle->vehicle_code }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Vehicle Number:</strong><br>
                                {{ $vehicle->vehicle_number }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <span class="{{ $vehicle->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                    {{ ucfirst($vehicle->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created Date:</strong><br>
                                {{ $vehicle->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $vehicle->updated_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>
                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
