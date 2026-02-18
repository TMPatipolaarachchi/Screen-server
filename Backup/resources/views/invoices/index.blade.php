@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Manage Invoices')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Invoice Management</h2>
            <p class="text-muted mb-0">Total Invoices: <strong>{{ $invoices->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create New Invoice
            </a>
        </div>
    </div>

    <!-- Status Filter Navigation -->
   <div class="card mb-4 shadow-sm">
        <div class="card-body py-3">

         <div class="col-md-6">
                    <h5 class="mb-4 text-muted">
                        <i class="fas fa-filter me-1"></i> Filter
                    </h5>
                    <div class="d-flex flex-wrap gap-4">
                        <a href="{{ route('invoices.index', array_merge(request()->except(['status']), ['status' => 'pending'])) }}" 
                           class="btn btn-{{ $statusFilter === 'pending' ? 'primary' : 'outline-primary' }} btn-sm">
                            <i class="fas fa-clock"></i> Pending ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('invoices.index', array_merge(request()->except(['status']), ['status' => 'complete'])) }}" 
                            class="btn btn-{{ $statusFilter === 'complete' ? 'primary' : 'outline-primary' }} btn-sm">
                            <i class="fas fa-check-circle"></i> Complete ({{ $completeCount }})
                        </a>
                        <a href="{{ route('invoices.index', array_merge(request()->except(['status']), ['status' => 'deleted'])) }}" 
                           class="btn btn-{{ $statusFilter === 'deleted' ? 'primary' : 'outline-primary' }} btn-sm">
                            <i class="fas fa-trash"></i> Deleted ({{ $deletedCount }})
                        </a>
                    </div>
                </div>
            <form method="get" action="{{ route('invoices.index') }}" class="row g-2 align-items-end flex-wrap">
                
                <div class="col-md-3">
                    <label for="supplier_id" class="form-label text-muted small mb-1">Supplier</label>
                    <select class="form-select form-select-sm" id="supplier_id" name="supplier_id">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label text-muted small mb-1">From Date</label>
                    <input type="date" class="form-control form-control-sm" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label text-muted small mb-1">To Date</label>
                    <input type="date" class="form-control form-control-sm" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex gap-2  ms-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="fas fa-filter me-1"></i>Apply
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Status Filter Navigation -->

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-body">
            @if ($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <tr class="text-center">
                                <th style="color: #000000 !important; font-weight: 600;">Invoice Number</th>
                                <th style="color: #000000 !important; font-weight: 600;">Date</th>
                                <th style="color: #000000 !important; font-weight: 600;">Supplier</th>
                                <th style="color: #000000 !important; font-weight: 600;">Total Amount</th>
                                <th style="color: #000000 !important; font-weight: 600;">Paid Amount</th>
                                <th style="color: #000000 !important; font-weight: 600;">Remaining</th>
                                <th style="color: #000000 !important; font-weight: 600;">Status</th>
                                <th style="color: #000000 !important; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                @php
                                    $paidAmount = $invoice->paid_amount ?? 0;
                                    $remainingAmount = $invoice->total_amount - $paidAmount;
                                @endphp
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $invoice->invoice_number }}</strong>
                                    </td>
                                    <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                    <td>{{ $invoice->supplier->name }}</td>
                                    <td>LKR {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="text-success">LKR {{ number_format($paidAmount, 2) }}</td>
                                    <td>
                                        <span class="{{ $remainingAmount > 0 ? 'text-warning' : 'text-success' }} fw-bold">
                                            LKR {{ number_format($remainingAmount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($invoice->status === 'pending')
                                            <span class="text-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($invoice->status === 'complete')
                                            <span class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Complete
                                            </span>
                                        @elseif($invoice->status === 'deleted')
                                            <span class="text-danger">
                                                <i class="fas fa-trash me-1"></i>Deleted
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View Invoice">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($invoice->status === 'complete')
                                            <button class="btn btn-sm btn-outline-secondary me-1" disabled title="Cannot edit completed invoices">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @elseif($invoice->status === 'deleted')
                                            <button class="btn btn-sm btn-outline-secondary me-1" disabled title="Cannot edit deleted invoices">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Invoice">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if($paidAmount == 0 && $invoice->status === 'pending')
                                            <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" class="d-inline" data-confirm-message="Are you sure you want to delete this invoice? This action will move the invoice to deleted status.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Invoice">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($invoice->status === 'deleted')
                                            <form method="POST" action="{{ route('invoices.restore', $invoice->id) }}" class="d-inline" data-confirm-message="Are you sure you want to restore this invoice? It will be moved back to pending status.">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore Invoice">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <p class="text-muted">No invoices found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No invoices found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
