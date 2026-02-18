@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Invoice Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Invoice Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Invoice Number:</strong><br>
                                {{ $invoice->invoice_number }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Invoice Date:</strong><br>
                                {{ $invoice->invoice_date->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="mb-2">
                                <strong>Supplier:</strong><br>
                                {{ $invoice->supplier->name }}
                            </p>
                        </div>
                    </div>

                    @if($invoice->vehicle || $invoice->employee || $invoice->helper)
                        <div class="row mb-4">
                            @if($invoice->vehicle)
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Vehicle:</strong><br>
                                        {{ $invoice->vehicle->vehicle_number }} 
                                    </p>
                                </div>
                            @endif
                            @if($invoice->employee)
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Driver:</strong><br>
                                        {{ $invoice->employee->name }}<br>
                                    </p>
                                </div>
                            @endif
                            @if($invoice->helper)
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Helper:</strong><br>
                                        {{ $invoice->helper->name }}<br>
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <hr>

                    <h5 class="mb-3">Invoice Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Item Code</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Cost Price (LKR)</th>
                                    <th class="text-end">Total (LKR)</th>
                                    <th class="text-end">VAT (LKR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $invoiceItem)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $invoiceItem->item->name }}
                                            @if($invoiceItem->item->vat_available)
                                                <span class="badge bg-success" style="font-size: 0.7em;">VAT</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoiceItem->item->item_code }}</td>
                                        <td>{{ $invoiceItem->quantity }}</td>
                                        <td class="text-end">{{ number_format($invoiceItem->cost_price, 2, '.', ',') }}</td>
                                        <td class="text-end">{{ number_format($invoiceItem->total, 2, '.', ',') }}</td>
                                        <td class="text-end">
                                            @if($invoiceItem->vat_amount > 0)
                                                {{ number_format($invoiceItem->vat_amount, 2, '.', ',') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end fw-bold">LKR {{ number_format($invoice->subtotal ?? 0, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end">Total VAT ({{ number_format($invoice->vat_percentage ?? 0, 2) }}%):</td>
                                    <td class="text-end">LKR {{ number_format($invoice->vat_amount ?? 0, 2, '.', ',') }}</td>
                                </tr>
                                @if($invoice->bank_charge > 0)
                                <tr>
                                    <td colspan="6" class="text-end">Bank Charge:</td>
                                    <td class="text-end">LKR {{ number_format($invoice->bank_charge ?? 0, 2, '.', ',') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">Grand Total:</td>
                                    <td class="text-end fw-bold text-primary">LKR {{ number_format($invoice->total_amount, 2, '.', ',') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr>

                    <h5 class="mb-3">Payment Setoffs</h5>
                    @if($invoice->paymentSetoffs && $invoice->paymentSetoffs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Payment Date</th>
                                        <th>Payment Method</th>
                                        <th>Reference</th>
                                        <th class="text-end">Amount (LKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->paymentSetoffs as $index => $setoff)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $setoff->payment->payment_date->format('d M Y') }}</td>
                                            <td>{{ ucfirst($setoff->payment->payment_method) }}</td>
                                            <td>
                                                @if($setoff->payment->payment_method === 'online')
                                                    {{ $setoff->payment->reference_number }}
                                                @else
                                                    {{ $setoff->payment->cheque_number }}
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($setoff->amount, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total Paid:</td>
                                        <td class="text-end fw-bold text-success">LKR {{ number_format($invoice->paid_amount, 2, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Remaining:</td>
                                        <td class="text-end fw-bold text-danger">LKR {{ number_format($invoice->total_amount - $invoice->paid_amount, 2, '.', ',') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No payment setoffs recorded for this invoice.
                        </div>
                    @endif

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created:</strong><br>
                                {{ $invoice->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $invoice->updated_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ request('return') ? urldecode(request('return')) : route('invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
