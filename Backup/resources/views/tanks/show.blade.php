@extends('layouts.app')

@section('body_class', 'page-tanks')

@section('title', 'Tank Details')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Tank Details Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Tank Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $tank->id }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Item:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $tank->item->name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Tank Name:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $tank->tank_name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Max Stock:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ number_format($tank->max_stock, 2) }} L
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-9">
                            @if ($tank->status === 'active')
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
                            {{ $tank->created_at->format('M d, Y - h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $tank->updated_at->format('M d, Y - h:i A') }}
                        </div>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                        <a href="{{ route('tanks.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Tanks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection