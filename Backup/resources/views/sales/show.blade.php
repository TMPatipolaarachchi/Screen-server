@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Sale Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Sale Record Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Record ID:</strong><br>
                                #{{ $sale->id }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <span class="{{ $sale->status === 'complete' ? 'text-success' : 'text-warning' }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Meter Name:</strong><br>
                                {{ $sale->meter->meter_name }}
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Pump Name:</strong><br>
                                {{ $sale->pump->name }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Before Meter Value:</strong><br>
                                {{ number_format($sale->last_meter_value, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Meter Value:</strong><br>
                                @if($sale->completion_meter_value)
                                    {{ number_format($sale->completion_meter_value, 2) }}
                                @else
                                    <span class="text-muted">Not completed</span>
                                @endif
                            </p>
                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Pumper Name:</strong><br>
                                {{ $sale->employee->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Item:</strong><br>
                                {{ $sale->meter->item->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Selling Price:</strong><br>
                                Rs. {{ number_format($sale->meter->item->selling_price ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Total Selling Price:</strong><br>
                                @if($sale->completion_meter_value)
                                    Rs. {{ number_format(($sale->completion_meter_value - $sale->last_meter_value) * ($sale->meter->item->selling_price ?? 0), 2) }}
                                @else
                                    <span class="text-muted">Not completed</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($sale->saleCustomers->count() > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">Customer Sales Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th class="text-end">Quantity (L)</th>
                                        <th class="text-end">Price (Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->saleCustomers as $saleCustomer)
                                    <tr>
                                        <td>{{ $saleCustomer->customer->name }}</td>
                                        <td>{{ $saleCustomer->customer->phone_number }}</td>
                                        <td class="text-end">{{ number_format($saleCustomer->quantity, 2) }}</td>
                                        <td class="text-end">{{ number_format($saleCustomer->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end">Total Sales:</th>
                                        <th class="text-end">Rs. {{ number_format($sale->saleCustomers->sum('price'), 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created Date:</strong><br>
                                {{ $sale->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $sale->updated_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('sales.status') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Status
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
