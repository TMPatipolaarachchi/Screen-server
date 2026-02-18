@extends('layouts.app')

@section('body_class', 'page-meters')

@section('title', 'Meter Details')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Meter Details Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Meter Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meter->id }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Meter Name:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meter->meter_name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Meter Value:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ number_format($meter->meter_value, 3) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-9">
                            @if ($meter->status === 'active')
                                <span class="text-success fw-bold">Active</span>
                            @else
                                <span class="text-warning fw-bold">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Created:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meter->created_at->format('M d, Y - h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meter->updated_at->format('M d, Y - h:i A') }}
                        </div>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                        <a href="{{ route('meters.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Meters
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection