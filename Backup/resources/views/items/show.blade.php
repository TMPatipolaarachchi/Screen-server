@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Item Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                       Item Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Item Code:</strong><br>
                                {{ $item->item_code }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Item Name:</strong><br>
                                {{ $item->name }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Category:</strong><br>
                                {{ $item->category->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <span class="{{ $item->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Cost Price:</strong><br>
                                LKR {{ number_format($item->cost_price, 5) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Selling Price:</strong><br>
                                LKR {{ number_format($item->selling_price, 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created:</strong><br>
                                {{ $item->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $item->updated_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
