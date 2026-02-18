@extends('layouts.app')

@section('body_class', 'page-payments')

@section('title', 'Payment Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Payment Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Payment Date:</strong><br>
                                {{ $payment->payment_date->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Payment Method:</strong><br>
                                <span class="{{ $payment->payment_method === 'online' ? 'text-success' : 'text-warning' }} fw-bold">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Supplier:</strong><br>
                                {{ $payment->supplier->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Amount:</strong><br>
                                <span class="text-danger fw-bold">LKR {{ number_format($payment->amount, 2, '.', ',') }}</span>
                            </p>
                        </div>
                    </div>

                    @if($payment->payment_method === 'online')
                        <hr>

                        <h5 class="mb-3">Online Payment Details</h5>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Bank Name:</strong><br>
                                    {{ $payment->bank->name }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Bank Account:</strong><br>
                                    {{ $payment->bank->account }}
                                </p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="mb-2">
                                    <strong>Reference Number:</strong><br>
                                    {{ $payment->reference_number }}
                                </p>
                            </div>
                        </div>
                    @else
                        <hr>

                        <h5 class="mb-3">Cheque Payment Details</h5>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="mb-2">
                                    <strong>Cheque Number:</strong><br>
                                    {{ $payment->cheque_number }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Total Amount:</strong><br>
                                <span class="text-primary fw-bold">LKR {{ number_format($payment->amount, 2, '.', ',') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Remaining Balance:</strong><br>
                                <span class="text-success fw-bold">LKR {{ number_format($payment->remaining_balance, 2, '.', ',') }}</span>
                            </p>
                        </div>
                    </div>

                    @if($payment->setoffs->count() > 0)
                        <hr>

                        <h5 class="mb-3">Payment Used for Invoices</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Amount</th>
                                        <th class="text-end">Amount Used</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment->setoffs as $setoff)
                                        <tr>
                                            <td>
                                                <a href="{{ route('invoices.show', $setoff->invoice_id) }}" class="text-decoration-none">
                                                    {{ $setoff->invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>{{ $setoff->invoice->invoice_date->format('d M Y') }}</td>
                                            <td>LKR {{ number_format($setoff->invoice->total_amount, 2, '.', ',') }}</td>
                                            <td class="text-end fw-bold">LKR {{ number_format($setoff->amount, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total Used:</strong></td>
                                        <td class="text-end fw-bold">LKR {{ number_format($payment->setoffs->sum('amount'), 2, '.', ',') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <hr>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This payment has not been used for any invoices yet.
                        </div>
                    @endif

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created:</strong><br>
                                {{ $payment->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $payment->updated_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ request('return') ? urldecode(request('return')) : route('payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <form method="POST" action="{{ route('payments.destroy', $payment->id) }}" 
                              class="d-inline" 
                              data-confirm-message="Are you sure you want to delete this payment? This action cannot be undone.">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
