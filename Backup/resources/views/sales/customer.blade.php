@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Customer Sales Details')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Customer Sales Details</h2>
            <p class="text-muted mb-0">
                <strong>{{ $customer->name }}</strong> - {{ $customer->phone_number }}
                @if($customer->address)
                    <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $customer->address }}</small>
                @endif
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('sales.status') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Sales Status
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Purchases</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6366f1;">{{ $totalSales }}</h2>
                            <small class="text-muted">Completed sales</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-shopping-cart fa-2x" style="color: #6366f1;"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #6366f1;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Quantity</h6>
                            <h2 class="mb-0 fw-bold text-info">{{ number_format($totalQuantity, 2) }} L</h2>
                            <small class="text-muted">Fuel purchased</small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-gas-pump fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Amount</h6>
                            <h2 class="mb-0 fw-bold text-success">Rs. {{ number_format($totalAmount, 2) }}</h2>
                            <small class="text-muted">Total spent</small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-coins fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    @if($paymentMethodBreakdown->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Method Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($paymentMethodBreakdown as $methodData)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                                @if($methodData['method'] === 'cash')
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-uppercase" style="font-size: 0.75rem; color: #6c757d;">Cash</h6>
                                        <h5 class="mb-0 fw-bold text-success">Rs. {{ number_format($methodData['total'], 2) }}</h5>
                                        <small class="text-muted">{{ $methodData['count'] }} transaction(s)</small>
                                    </div>
                                @elseif($methodData['method'] === 'cheque')
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-file-invoice fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-uppercase" style="font-size: 0.75rem; color: #6c757d;">Cheque</h6>
                                        <h5 class="mb-0 fw-bold text-warning">Rs. {{ number_format($methodData['total'], 2) }}</h5>
                                        <small class="text-muted">{{ $methodData['count'] }} transaction(s)</small>
                                    </div>
                                @elseif($methodData['method'] === 'online')
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-credit-card fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-uppercase" style="font-size: 0.75rem; color: #6c757d;">Online</h6>
                                        <h5 class="mb-0 fw-bold text-info">Rs. {{ number_format($methodData['total'], 2) }}</h5>
                                        <small class="text-muted">{{ $methodData['count'] }} transaction(s)</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Sales History -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Sales History</h5>
        </div>
        <div class="card-body">
            @if($salesData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Sale ID</th>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Meter</th>
                                <th>Pump</th>
                                <th>Pumper</th>
                                <th class="text-end">Quantity (L)</th>
                                <th class="text-end">Price/L (Rs.)</th>
                                <th class="text-end">Amount (Rs.)</th>
                                <th class="text-center">Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesData as $sale)
                            <tr class="text-center">
                                <td><strong>#{{ $sale->sale_id }}</strong></td>
                                <td><small>{{ \Carbon\Carbon::parse($sale->updated_at)->format('M d, Y h:i A') }}</small></td>
                                <td>{{ $sale->item_name }}</td>
                                <td>{{ $sale->meter_name }}</td>
                                <td>{{ $sale->pump_name }}</td>
                                <td>{{ $sale->employee_name }}</td>
                                <td class="text-end">{{ number_format($sale->quantity, 2) }}</td>
                                <td class="text-end">{{ number_format($sale->selling_price, 2) }}</td>
                                <td class="text-end"><strong class="text-success">Rs. {{ number_format($sale->price, 2) }}</strong></td>
                                <td class="text-center">
                                    @if($sale->payment_method === 'cash')
                                        <span class="text-success"><i class="fas fa-money-bill-wave"></i> Cash</span>
                                    @elseif($sale->payment_method === 'cheque')
                                        <span class="text-warning"><i class="fas fa-file-invoice"></i> Cheque</span>
                                    @elseif($sale->payment_method === 'online')
                                        <span class="text-info"><i class="fas fa-credit-card"></i> Online</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light text-center">
                            <tr>
                                <th colspan="6" class="text-end">Totals:</th>
                                <th class="text-end">{{ number_format($totalQuantity, 2) }} L</th>
                                <th></th>
                                <th class="text-end">Rs. {{ number_format($totalAmount, 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No sales found for this customer.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
