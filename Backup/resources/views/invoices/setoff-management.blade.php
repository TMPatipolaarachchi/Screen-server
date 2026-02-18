@extends('layouts.app')

@section('body_class', 'page-invoices')

@section('title', 'Payment Setoff Management')

@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-dark">Payment Setoff Management</h2>
            </div>

          

            <!-- Invoices List -->
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold">Invoices</h5>
                </div>
                <div class="card-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>Supplier</th>
                                        <th class="text-end">Total Amount</th>
                                        <th class="text-end">Paid Amount</th>
                                        <th class="text-end">Remaining</th>
                                        <th>Payment Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr class="text-center">
                                            <td>
                                                <a href="{{ route('invoices.show', $invoice) }}" class="text-decoration-none">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                            <td>{{ $invoice->supplier->name }}</td>
                                            <td class="text-end">LKR {{ number_format($invoice->total_amount, 2) }}</td>
                                            <td class="text-end fw-bold">LKR {{ number_format($invoice->paid_amount, 2) }}</td>
                                            <td class="text-end fw-bold">
                                                LKR {{ number_format($invoice->remaining_amount, 2) }}
                                            </td>
                                            <td>
                                                @if($invoice->remaining_amount == 0)
                                                    <span class="text-success fw-bold">Fully Paid</span>
                                                @elseif($invoice->paid_amount > 0)
                                                    <span class="text-warning fw-bold">Partially Paid</span>
                                                @else
                                                    <span class="text-danger fw-bold">Unpaid</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($invoice->remaining_amount > 0)
                                                    <form method="GET" action="{{ route('invoices.showSetoff') }}" style="display: inline-block;">
                                                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Add Payments">
                                                            <i class="fas fa-plus"></i> Add Payments
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">No action needed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No invoices found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
