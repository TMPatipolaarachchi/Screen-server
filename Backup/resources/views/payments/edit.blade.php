@extends('layouts.app')

@section('body_class', 'page-payments')

@section('title', 'Edit Payment')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Edit Payment Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Edit Payment
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.update', $payment->id) }}" id="editPaymentForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                           id="payment_date" name="payment_date" 
                                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                            id="supplier_id" name="supplier_id" required>
                                        <option value="">-- Select Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $payment->supplier_id) == $supplier->id)>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="online" @selected(old('payment_method', $payment->payment_method) == 'online')>Online</option>
                                        <option value="cheque" @selected(old('payment_method', $payment->payment_method) == 'cheque')>Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           id="amount" name="amount"
                                           value="{{ old('amount', $payment->amount) }}" required
                                           step="0.01" min="0" max="999999999.99"
                                           autocomplete="off">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                                                    </div>

                        <!-- Online Payment Fields -->
                        <div class="row" id="onlineFields" style="display: {{ old('payment_method', $payment->payment_method) === 'online' ? 'flex' : 'none' }};">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bank_id" class="form-label">Bank Account <span class="text-danger">*</span></label>
                                    <select class="form-select @error('bank_id') is-invalid @enderror" 
                                            id="bank_id" name="bank_id">
                                        <option value="">-- Select Bank --</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" @selected(old('bank_id', $payment->bank_id) == $bank->id)>
                                                {{ $bank->name }} ({{ $bank->account }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">Reference Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                           id="reference_number" name="reference_number" 
                                           value="{{ old('reference_number', $payment->reference_number) }}">
                                    @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cheque Payment Fields -->
                        <div class="row" id="chequeFields" style="display: {{ old('payment_method', $payment->payment_method) === 'cheque' ? 'flex' : 'none' }};">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cheque_number" class="form-label">Cheque Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('cheque_number') is-invalid @enderror" 
                                           id="cheque_number" name="cheque_number" 
                                           value="{{ old('cheque_number', $payment->cheque_number) }}">
                                    @error('cheque_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethod = document.getElementById('payment_method');
        const onlineFields = document.getElementById('onlineFields');
        const chequeFields = document.getElementById('chequeFields');
        const bankId = document.getElementById('bank_id');
        const referenceNumber = document.getElementById('reference_number');
        const chequeNumber = document.getElementById('cheque_number');

        function togglePaymentFields() {
            const method = paymentMethod.value;
            
            if (method === 'online') {
                onlineFields.style.display = 'flex';
                chequeFields.style.display = 'none';
                bankId.required = true;
                referenceNumber.required = true;
                chequeNumber.required = false;
                chequeNumber.value = '';
            } else if (method === 'cheque') {
                onlineFields.style.display = 'none';
                chequeFields.style.display = 'flex';
                bankId.required = false;
                referenceNumber.required = false;
                chequeNumber.required = true;
                bankId.value = '';
                referenceNumber.value = '';
            } else {
                onlineFields.style.display = 'none';
                chequeFields.style.display = 'none';
                bankId.required = false;
                referenceNumber.required = false;
                chequeNumber.required = false;
            }
        }

        paymentMethod.addEventListener('change', togglePaymentFields);
        
        // Initialize on page load
        togglePaymentFields();
    });
</script>
@endsection
