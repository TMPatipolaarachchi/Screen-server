@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Sales Status')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Sales Status & Reports</h2>
            <p class="text-muted mb-0">Total Records: <strong>{{ $sales->count() }}</strong></p>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Selling Price</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6366f1;">Rs. {{ number_format($totalSellingPrice, 2) }}</h2>
                          
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x" style="color: #6366f1;"></i>
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
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Customer Sales</h6>
                            <h2 class="mb-0 fw-bold text-success">Rs. {{ number_format($totalSales, 2) }}</h2>
                           
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
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Difference</h6>
                            <h2 class="mb-0 fw-bold" style="color: {{ $totalSellingPrice - $totalSales >= 0 ? '#10b981' : '#ef4444' }};">
                                Rs. {{ number_format(abs($totalSellingPrice - $totalSales), 2) }}
                            </h2>
                           
                        </div>
                        <div class="rounded-circle p-3" style="background-color: {{ $totalSellingPrice - $totalSales >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};">
                            <i class="fas fa-balance-scale fa-2x" style="color: {{ $totalSellingPrice - $totalSales >= 0 ? '#10b981' : '#ef4444' }};"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background-color: {{ $totalSellingPrice - $totalSales >= 0 ? '#10b981' : '#ef4444' }};"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer-wise Breakdown -->
    @if($customerBreakdown->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Customer-wise Sales Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Total Quantity (L)</th>
                                    <th>Total Amount (Rs.)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customerBreakdown as $customer)
                                <tr class="text-center">
                                    <td><strong>{{ $customer->name }}</strong></td>
                                    <td>{{ $customer->phone_number }}</td>
                                    <td>{{ number_format($customer->total_quantity, 2) }}</td>
                                    <td><strong class="text-success">Rs. {{ number_format($customer->total_price, 2) }}</strong></td>
                    
                                    <td>
                                        <a href="{{ route('sales.customer', $customer->customer_id) }}" class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Sales Records -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Sales Records</h5>
        </div>
        <div class="card-body">
            @if ($sales->count() > 0)
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-3" id="salesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            <i class="fas fa-clock me-1"></i> Pending Sales <span class="text-warning text-dark ms-1">{{ $sales->where('status', 'pending')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="complete-tab" data-bs-toggle="tab" data-bs-target="#complete" type="button" role="tab">
                            <i class="fas fa-check-circle me-1"></i> Completed Sales <span class="text-success ms-1">{{ $sales->where('status', 'complete')->count() }}</span>
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="salesTabsContent">
                    <!-- Pending Sales Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        @if($sales->where('status', 'pending')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>Meter Name</th>
                                            <th>Pump Name</th>
                                            <th>Pumper Name</th>
                                            <th>Before Value</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales->where('status', 'pending') as $sale)
                                            <tr class="text-center">
                                                <td><strong>#{{ $sale->id }}</strong></td>
                                                <td>{{ $sale->meter->meter_name }}</td>
                                                <td>{{ $sale->pump->name }}</td>
                                                <td>{{ $sale->employee->name }}</td>
                                                <td>{{ number_format($sale->last_meter_value, 2) }}</td>
                                                <td><small>{{ $sale->created_at->format('M d, Y h:i A') }}</small></td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('sales.complete', $sale->id) }}" class="btn btn-sm btn-outline-success" title="Complete Sale">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                             <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No pending sales found in the system.
                </div>
                        @endif
                    </div>

                    <!-- Completed Sales Tab -->
                    <div class="tab-pane fade" id="complete" role="tabpanel">
                        @if($sales->where('status', 'complete')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>Meter Name</th>
                                            <th>Pump Name</th>
                                            <th>Pumper Name</th>
                                            <th>Before Value</th>
                                            <th>Last Value</th>
                                            <th>Total Sales</th>
                                            <th>Completed Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales->where('status', 'complete') as $sale)
                                            <tr class="text-center">
                                                <td><strong>#{{ $sale->id }}</strong></td>
                                                <td>{{ $sale->meter->meter_name }}</td>
                                                <td>{{ $sale->pump->name }}</td>
                                                <td>{{ $sale->employee->name }}</td>
                                                <td>{{ number_format($sale->last_meter_value, 2) }}</td>
                                                <td>{{ number_format($sale->completion_meter_value, 2) }}</td>
                                                <td><strong class="text-success">Rs. {{ number_format($sale->saleCustomers->sum('price'), 2) }}</strong></td>
                                                <td><small>{{ $sale->updated_at->format('M d, Y h:i A') }}</small></td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                             <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No sales found in the system.
                </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No completed sales found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
