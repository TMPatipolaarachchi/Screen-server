@extends('layouts.app')

@section('body_class', 'page-payments')

@section('title', 'Payment Management')

@section('content')
<div class="container-fluid mt-4 mb-5" style="position: relative; z-index: 1;">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-black">Payment Management</h2>
            <p class="text-black mb-0">Total Payments: <strong>{{ $payments->total() }}</strong></p>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" id="togglePaymentFormBtn" onclick="togglePaymentForm()">
                <i class="fas fa-plus-circle me-2"></i>Add New Payment
            </button>
        </div>
    </div>

<div class="container-fluid mt-4 mb-5">
    <!-- Status Filter Navigation -->
        
    <div class="card mb-4 shadow-sm">
        <div class="card-body py-3">

         <div class="col-md-6">
                    <h5 class="mb-4 text-muted">
                        <i class="fas fa-filter me-1"></i> Filter
                    </h5>
                    <div class="d-flex flex-wrap gap-4">
                        <a href="{{ route('payments.index', array_merge(request()->except(['status']), ['status' => 'pending'])) }}" 
                           class="btn btn-{{ $statusFilter === 'pending' ? 'primary' : 'outline-primary' }} btn-sm">
                            <i class="fas fa-clock me-1"></i> Pending ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('payments.index', array_merge(request()->except(['status']), ['status' => 'complete'])) }}" 
                           class="btn btn-{{ $statusFilter === 'complete' ? 'primary' : 'outline-primary' }} btn-sm">
                            <i class="fas fa-check-circle me-1"></i> Complete ({{ $completeCount }})
                        </a>
                        <a href="{{ route('payments.index', array_merge(request()->except(['status']), ['status' => 'deleted'])) }}" 
                           class="btn btn-{{ $statusFilter === 'deleted' ? 'primary' : 'outline-primary' }} btn-sm">
                            <i class="fas fa-trash me-1"></i> Deleted ({{ $deletedCount }})
                        </a>
                    </div>
                </div>

            <form method="get" action="" class="row g-2 align-items-end flex-wrap">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                
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

    <!-- Payment Form -->
    <div class="card mb-4" id="paymentFormCard" style="display: none;">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Add Payment</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePaymentForm()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" name="supplier_id" required>
                                <option value="">-- Supplier Name --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">-- Select Method --</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <!-- Online Payment Fields -->
                <div class="row" id="onlineFields" style="display: none;">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="bank_id" class="form-label">Bank Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('bank_id') is-invalid @enderror" 
                                    id="bank_id" name="bank_id">
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" data-account="{{ $bank->account }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name }} - {{ $bank->account }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                   id="reference_number" name="reference_number" value="{{ old('reference_number') }}">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cheque Payment Fields -->
                <div class="row" id="chequeFields" style="display: none;">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cheque_number" class="form-label">Cheque Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cheque_number') is-invalid @enderror" 
                                   id="cheque_number" name="cheque_number" value="{{ old('cheque_number') }}"
                                   maxlength="6" minlength="6" pattern="[0-9]{6}" 
                                   placeholder="enter cheque number"
                                   title="Please enter exactly 6 digits">
                            @error('cheque_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   id="amount" name="amount" value="{{ old('amount') }}" required
                                   step="0.01" min="0" max="999999999.99"
                                   autocomplete="off">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            Record Payment
                        </button>
                        <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold"></i>Payment Records</h5>
        </div>
        <div class="card-body">
            @if ($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Payment ID</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Method</th>
                                <th>Bank/Cheque Details</th>
                                <th>Amount</th>
                                <th>Remaining Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @php
                                    $canEdit = $payment->status === 'pending' && $payment->canEdit();
                                    $canDelete = $payment->status === 'pending' && $payment->canDelete();
                                @endphp
                                <tr class="{{ $payment->status === 'deleted' ? 'table-secondary' : '' }} text-center">
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td><strong>{{ $payment->supplier->name }}</strong></td>
                                    <td>
                                        <span class="text-{{ $payment->payment_method === 'online' ? 'success' : 'warning' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->payment_method === 'online')
                                            <small>
                                                <strong>Bank:</strong> {{ $payment->bank->name }}<br>
                                                <strong>Acc:</strong> {{ $payment->bank->account }}<br>
                                                <strong>Ref #:</strong> {{ $payment->reference_number }}
                                            </small>
                                        @else
                                            <small>
                                                <strong>Cheque #:</strong> {{ $payment->cheque_number }}
                                            </small>
                                        @endif
                                    </td>
                                    <td><strong>Rs. {{ number_format($payment->amount, 2) }}</strong></td>
                                    <td><strong>Rs. {{ number_format($payment->remaining_balance, 2) }}</strong></td>
                                    <td>
                                        @if($payment->status === 'deleted')
                                            <span class="text-danger">
                                                <i class="fas fa-trash me-1"></i>Deleted
                                            </span>
                                        @elseif($payment->status === 'complete')
                                            <span class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Complete
                                            </span>
                                        @else
                                            <span class="text-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'deleted')
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View payment details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary me-1" disabled title="Cannot edit deleted payments">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('payments.restore', $payment->id) }}" class="d-inline" data-confirm-message="Are you sure you want to restore this payment?">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        title="Restore payment">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View payment details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($canEdit)
                                                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit payment">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary me-1" disabled title="Cannot edit complete or deleted payments">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            @if($canDelete)
                                                <form method="POST" action="{{ route('payments.toggle-status', $payment->id) }}" class="d-inline" data-confirm-message="Are you sure you want to delete this payment?">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete payment">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete payments used in setoffs">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No payment records found. Add your first payment above.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .fas.fa-trash {
        color: black !important;
    }

    .supplier-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .supplier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #paymentFormCard {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
function togglePaymentForm() {
    const formCard = document.getElementById('paymentFormCard');
    const toggleBtn = document.getElementById('togglePaymentFormBtn');
    if (formCard.style.display === 'none') {
        formCard.style.display = 'block';
        setTimeout(() => {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
        toggleBtn.innerHTML = '<i class="fas fa-minus-circle me-2"></i>Hide Form';
        toggleBtn.classList.remove('btn-primary');
        toggleBtn.classList.add('btn-secondary');
    } else {
        formCard.style.display = 'none';
        toggleBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Add New Payment';
        toggleBtn.classList.remove('btn-secondary');
        toggleBtn.classList.add('btn-primary');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    const onlineFields = document.getElementById('onlineFields');
    const chequeFields = document.getElementById('chequeFields');
    const bankIdField = document.getElementById('bank_id');
    const referenceNumberField = document.getElementById('reference_number');
    const chequeNumberField = document.getElementById('cheque_number');

    function togglePaymentFields() {
        const method = paymentMethodSelect.value;
        if (method === 'online') {
            onlineFields.style.display = 'flex';
            chequeFields.style.display = 'none';
            bankIdField.required = true;
            referenceNumberField.required = true;
            chequeNumberField.required = false;
            chequeNumberField.value = '';
        } else if (method === 'cheque') {
            onlineFields.style.display = 'none';
            chequeFields.style.display = 'flex';
            bankIdField.required = false;
            referenceNumberField.required = false;
            chequeNumberField.required = true;
            bankIdField.value = '';
            referenceNumberField.value = '';
        } else {
            onlineFields.style.display = 'none';
            chequeFields.style.display = 'none';
            bankIdField.required = false;
            referenceNumberField.required = false;
            chequeNumberField.required = false;
        }
    }
    paymentMethodSelect.addEventListener('change', togglePaymentFields);
    togglePaymentFields();
});

function resetForm() {
    document.getElementById('paymentForm').reset();
    document.getElementById('onlineFields').style.display = 'none';
    document.getElementById('chequeFields').style.display = 'none';
}

// Balance toggle functions
@foreach($suppliers as $supplier)
function toggleBalance{{ $supplier->id }}() {
    const section = document.getElementById('balanceSection{{ $supplier->id }}');
    const btnText = document.getElementById('btnText{{ $supplier->id }}');
    if (section.style.display === 'none') {
        section.style.display = 'block';
        btnText.textContent = 'Hide Balance';
    } else {
        section.style.display = 'none';
        btnText.textContent = 'View Balance';
    }
}
@endforeach
</script>
@endsection
