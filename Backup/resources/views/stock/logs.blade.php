@extends('layouts.app')

@section('body_class', 'page-stock')

@section('title', 'Stock History - ' . $item->name)

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Stock History</h2>
        </div>
    </div>

    <!-- Item Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold">Item Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-2"><strong>Item Code:</strong> {{ $item->item_code }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-2"><strong>Name:</strong> {{ $item->name }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-2"><strong>Category:</strong> {{ $item->category->name }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-2"><strong>Current Stock:</strong> 
                        <span class="fw-bold">
                            {{ number_format($item->stock_quantity, 2) }} L
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock History Table -->
    <div class="card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold">Stock Movement History</h5>
        </div>
        <div class="card-body">
            @if ($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <tr>
                                <th style="color: #000000 !important; font-weight: 600;">Date & Time</th>
                                <th style="color: #000000 !important; font-weight: 600;">Type</th>
                                <th style="color: #000000 !important; font-weight: 600;">Tank</th>
                                <th style="color: #000000 !important; font-weight: 600;">Before</th>
                                <th style="color: #000000 !important; font-weight: 600;">Change</th>
                                <th style="color: #000000 !important; font-weight: 600;">After</th>
                                <th style="color: #000000 !important; font-weight: 600;">Reason</th>
                                <th style="color: #000000 !important; font-weight: 600;">User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        @if($log->type === 'increase')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-up"></i> Increase
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-down"></i> Decrease
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->tank)
                                            <span class="badge bg-info">{{ $log->tank->tank_name }}</span>
                                        @else
                                            <span class="text-muted small"><em>No tank</em></span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($log->before_quantity, 2) }} L</td>
                                    <td>
                                        <strong class="{{ $log->type === 'increase' ? 'text-success' : 'text-danger' }}">
                                            {{ $log->type === 'increase' ? '+' : '-' }}{{ number_format($log->quantity_change, 2) }} L
                                        </strong>
                                    </td>
                                    <td>{{ number_format($log->after_quantity, 2) }} L</td>
                                    <td>{{ $log->reason }}</td>
                                    <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0" role="alert">
                    <i class="fas fa-info-circle"></i> No stock history found for this item.
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('stock.category', $item->category_id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Stock
        </a>
    </div>
</div>
<style>
    .breadcrumb {
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #6c757d;
    }

    .breadcrumb-item a {
        color: #0d6efd;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    h2.fw-bold, h5.fw-bold {
        color: black;
    }

    .collapse {
        transition: height 0.35s ease;
    }

    .form-label.fw-semibold {
        color: #2c3e50;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #dee2e6;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endsection
