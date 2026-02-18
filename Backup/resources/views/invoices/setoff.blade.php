@extends('layouts.app')

@section('body_class', 'page-invoices')

@section('title', 'Invoice Payment Setoff')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Setoff Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Payment Setoff for Invoice
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Invoice Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Invoice Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Invoice Number:</th>
                                    <td>{{ $invoiceData['invoice_number'] }}</td>
                                </tr>
                                <tr>
                                    <th>Invoice Date:</th>
                                    <td>{{ date('d M Y', strtotime($invoiceData['invoice_date'])) }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier:</th>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Setoff Summary</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if(isset($invoiceData['is_existing']) && $invoiceData['is_existing'])
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Remaining Amount:</span>
                                            <span class="fw-bold text-danger">LKR {{ number_format($invoiceData['remaining_amount'], 2, '.', ',') }}</span>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Invoice Amount:</span>
                                            <span class="fw-bold">LKR {{ number_format($invoiceData['total_amount'], 2, '.', ',') }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Selected Payments:</span>
                                        <span class="fw-bold text-success" id="selectedAmount">LKR 0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between" id="remainingSection">
                                        <span class="fw-bold">New Remaining:</span>
                                        <span class="fw-bold" id="remainingAmount">LKR {{ number_format($invoiceData['is_existing'] ? $invoiceData['remaining_amount'] : $invoiceData['total_amount'], 2, '.', ',') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Payments -->
                    <form method="POST" action="{{ route('invoices.processSetoff') }}" id="setoffForm">
                        @csrf
                        <input type="hidden" name="invoice_data" value="{{ json_encode($invoiceData) }}">
                        <input type="hidden" name="invoice_items" value="{{ json_encode($invoiceItems) }}">
                        
                        <h5 class="mb-3">Available Payments from {{ $supplier->name }}</h5>
                        
                        @if($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;">Select</th>
                                            <th style="width: 12%;">Payment Date</th>
                                            <th style="width: 10%;">Method</th>
                                            <th style="width: 13%;">Reference</th>
                                            <th style="width: 13%;">Total Amount</th>
                                            <th style="width: 12%;">Used Amount</th>
                                            <th style="width: 12%;">Available</th>
                                            <th style="width: 23%;">Amount to Use<br><small class="text-muted">(LKR, 2 decimals)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            @php
                                                // Calculate used and available amounts
                                                $usedAmount = 0;
                                                $setoffsCount = 0;
                                                
                                                if ($payment->relationLoaded('setoffs') && $payment->setoffs) {
                                                    $setoffsCount = $payment->setoffs->count();
                                                    $usedAmount = $payment->setoffs->sum('amount');
                                                }
                                                
                                                // Convert to float for accurate comparison
                                                $totalAmount = (float) $payment->amount;
                                                $usedAmount = (float) $usedAmount;
                                                $availableAmount = $totalAmount - $usedAmount;
                                                
                                                // Round to 2 decimal places to avoid floating point issues
                                                $availableAmount = round($availableAmount, 2);
                                                
                                                // Payment is selectable if available > 0
                                                $isSelectable = $availableAmount > 0;
                                            @endphp
                                            <tr class="payment-row {{ !$isSelectable ? 'table-secondary' : '' }}">
                                                <td class="text-center">
                                                    @if($isSelectable)
                                                        <input type="checkbox" 
                                                               class="form-check-input custom-checkbox payment-checkbox" 
                                                               name="selected_payments[]" 
                                                               value="{{ $payment->id }}"
                                                               data-available="{{ $availableAmount }}"
                                                               onchange="togglePaymentRow(this)">
                                                    @else
                                                        <span class="text-muted" title="Fully used">✓</span>
                                                    @endif
                                                </td>
                                                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                                <td>
                                                    @if($payment->payment_method === 'online')
                                                        {{ $payment->reference_number }}
                                                    @else
                                                        {{ $payment->cheque_number }}
                                                    @endif
                                                </td>
                                                <td>LKR {{ number_format($totalAmount, 2, '.', ',') }}</td>
                                                <td class="{{ $usedAmount > 0 ? 'text-info fw-bold' : '' }}">
                                                    LKR {{ number_format($usedAmount, 2, '.', ',') }}
                                                    @if($setoffsCount > 0)
                                                        <small class="text-muted d-block">({{ $setoffsCount }} invoice{{ $setoffsCount > 1 ? 's' : '' }})</small>
                                                    @endif
                                                </td>
                                                <td class="fw-bold {{ $isSelectable ? 'text-success' : 'text-danger' }}">
                                                    LKR {{ number_format($availableAmount, 2, '.', ',') }}
                                                </td>
                                                <td>
                                                    @if($isSelectable)
                                                        <input type="number"
                                                               class="form-control amount-input"
                                                               name="payment_amounts[{{ $payment->id }}]"
                                                               value="0.00"
                                                               disabled
                                                               data-payment-id="{{ $payment->id }}"
                                                               data-available="{{ $availableAmount }}"
                                                               autocomplete="off"
                                                               step="0.01"
                                                               min="0"
                                                               max="{{ $availableAmount }}"
                                                               placeholder="0.00"
                                                               onchange="updateTotals()">
                                                    @else
                                                        <span class="text-muted">Fully Used</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('invoices.create') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="button" class="btn btn-primary" id="setoffSubmitBtn" onclick="disableAndSubmitSetoff()">
                                    <i class="fas fa-check"></i> Complete Invoice with Setoff
                                </button>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No payments available for this supplier. 
                                You need to add payments before creating invoices.
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('invoices.create') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <a href="{{ route('payments.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Payment
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const isExisting = {{ isset($invoiceData['is_existing']) && $invoiceData['is_existing'] ? 'true' : 'false' }};
const invoiceTotal = {{ $invoiceData['is_existing'] ? $invoiceData['remaining_amount'] : $invoiceData['total_amount'] }};

function togglePaymentRow(checkbox) {
    const row = checkbox.closest('.payment-row');
    const amountInput = row.querySelector('.amount-input');
    const available = parseFloat(checkbox.dataset.available);
    
    if (checkbox.checked) {
        amountInput.disabled = false;
        // Fill the full available amount (or remaining if less)
        const maxAmount = Math.min(available, parseFloat(getCurrentNewRemaining()));
        amountInput.value = maxAmount.toFixed(2);
    } else {
        amountInput.disabled = true;
        amountInput.value = '0.00';
    }
    
    updateTotals();
}

function getCurrentSelectedTotal() {
    let total = 0;
    document.querySelectorAll('.amount-input:not([disabled])').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    return total;
}

function getCurrentNewRemaining() {
    const selectedTotal = getCurrentSelectedTotal();
    return invoiceTotal - selectedTotal;
}

function updateTotals() {
    const selectedTotal = getCurrentSelectedTotal();
    const difference = invoiceTotal - selectedTotal;
    
    const formattedSelectedTotal = selectedTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('selectedAmount').textContent = 'LKR ' + formattedSelectedTotal;
    
    if (difference >= 0) {
        // Show remaining
        document.getElementById('remainingSection').style.display = 'flex';
        
        const formattedDifference = difference.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('remainingAmount').textContent = 'LKR ' + formattedDifference;
        
        const remainingElement = document.getElementById('remainingAmount');
        if (difference === 0) {
            remainingElement.classList.remove('text-danger');
            remainingElement.classList.add('text-success');
        } else {
            remainingElement.classList.remove('text-danger', 'text-success');
        }
    } 
}

function submitSetoff() {
    const selectedTotal = getCurrentSelectedTotal();
    const difference = invoiceTotal - selectedTotal;

    if (difference > 0) {
        const formattedDifference = difference.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        showSetoffModal(`<span class='text-dark'>The invoice has a remaining balance of <strong>LKR ${formattedDifference}</strong>. Do you want to proceed?</span>`, function() {
            proceedSetoff();
        });
        return;
    }
    proceedSetoff();
}

function proceedSetoff() {
    // Check if at least one payment is selected
    const checkedPayments = document.querySelectorAll('.payment-checkbox:checked');
    if (checkedPayments.length === 0) {
        showSetoffModal('Please select at least one payment to setoff against this invoice.');
        return;
    }

    // Validate all selected payment amounts
    let isValid = true;
    checkedPayments.forEach(checkbox => {
        const row = checkbox.closest('.payment-row');
        const amountInput = row.querySelector('.amount-input');
        const amount = parseFloat(amountInput.value) || 0;
        const available = parseFloat(checkbox.dataset.available);

        if (amount <= 0) {
            showSetoffModal('Please enter a valid amount for all selected payments.');
            isValid = false;
            return;
        }

        if (amount > available) {
            const formattedAvailable = available.toFixed(2);
            showSetoffModal(`The amount for one of the payments exceeds the available amount (<strong>LKR ${formattedAvailable}</strong>).`);
            isValid = false;
            return;
        }
    });  

    if (!isValid) {
        return;
    }

    document.getElementById('setoffForm').submit();
}

function showSetoffModal(message, onConfirm) {
    let modal = document.getElementById('setoffModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'setoffModal';
        modal.className = 'modal fade';
        modal.tabIndex = -1;
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning-subtle">
                        <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle me-2"></i>Notice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="setoffModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="setoffModalConfirmBtn" style="display:none;">Proceed</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    document.getElementById('setoffModalBody').innerHTML = message;
    const confirmBtn = document.getElementById('setoffModalConfirmBtn');
    if (onConfirm) {
        confirmBtn.style.display = '';
        confirmBtn.onclick = function() {
            const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
            bsModal.hide();
            onConfirm();
        };
    } else {
        confirmBtn.style.display = 'none';
        confirmBtn.onclick = null;
    }
    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
    bsModal.show();
}

// Auto-fill amounts when checkbox is clicked
document.addEventListener('DOMContentLoaded', function() {
    updateTotals();
    
    // Add validation to amount inputs
    document.querySelectorAll('.amount-input').forEach(input => {
        input.addEventListener('input', function() {
            const checkbox = this.closest('.payment-row').querySelector('.payment-checkbox');
            const available = parseFloat(checkbox.dataset.available);
            const currentValue = parseFloat(this.value) || 0;
            
            // Validate against available amount
            if (currentValue > available) {
                this.classList.add('is-invalid');
                this.value = available.toFixed(2);
            } else {
                this.classList.remove('is-invalid');
            }
            
            updateTotals();
        });
        
        input.addEventListener('blur', function() {
            let value = parseFloat(this.value) || 0;
            this.value = value.toFixed(2);
        });
    });
});
function disableAndSubmitSetoff() {
    var btn = document.getElementById('setoffSubmitBtn');
    btn.disabled = true;
    submitSetoff();
}
</script>
@endsection
