@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Complete Sale')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Complete Sale Section -->
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Complete Sale Record
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Sale Information Display -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">Sale Information</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Meter Name:</strong> {{ $sale->meter->meter_name }}</p>
                                <p class="mb-2"><strong>Pump Name:</strong> {{ $sale->pump->name }}</p>
                                <p class="mb-2"><strong>Pumper:</strong> {{ $sale->employee->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Item:</strong> {{ $sale->meter->item->name ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Selling Price:</strong> Rs. {{ number_format($sale->meter->item->selling_price ?? 0, 2) }}</p>
                                <p class="mb-0"><strong>Before Meter Value:</strong> {{ number_format($sale->last_meter_value, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('sales.processComplete', $sale->id) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Before Meter Value (Read-only display) -->
                        <div class="mb-3">
                            <label for="before_meter_value" class="form-label">Before Meter Value</label>
                            <input
                                type="text"
                                class="form-control"
                                id="before_meter_value"
                                value="{{ number_format($sale->last_meter_value, 2) }}"
                                readonly
                                disabled
                            />
                        </div>

                        <!-- Completion Meter Value (Current/Last Value) -->
                        <div class="mb-3">
                            <label for="completion_meter_value" class="form-label">Last Meter Value <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('completion_meter_value') is-invalid @enderror"
                                id="completion_meter_value"
                                name="completion_meter_value"
                                value="{{ old('completion_meter_value') }}"
                                required
                            />
                            @error('completion_meter_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Selling Price (Auto-calculated) -->
                        <div class="mb-3">
                            <label for="total_selling_price" class="form-label">Total Selling Price</label>
                            <input
                                type="text"
                                class="form-control bg-light"
                                id="total_selling_price"
                                readonly
                                value="Rs. 0.00"
                            />
                            <small class="text-muted">(Last Meter Value - Before Meter Value) × Selling Price</small>
                        </div>

                        <!-- Customer Sales Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Customer Sales <span class="text-danger">*</span></h5>
                            
                            <div id="customerRows">
                                <!-- Customer rows will be added dynamically -->
                            </div>

                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-end">
                                    <h5 class="mb-0">Total Sales: <span id="total_sales" class="text-primary">Rs. 0.00</span></h5>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                Complete Sale
                            </button>
                            <a href="{{ route('sales.status') }}" class="btn btn-secondary">
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
        const sellingPrice = {{ $sale->meter->item->selling_price ?? 0 }};
        const beforeMeterValue = {{ $sale->last_meter_value }};
        const customerRows = document.getElementById('customerRows');
        const totalSellingPriceInput = document.getElementById('total_selling_price');
        const completionMeterInput = document.getElementById('completion_meter_value');
        let rowCounter = 0;

        const customers = @json($customers);

        // Calculate total selling price based on meter values
        function calculateTotalSellingPrice() {
            const completionValue = parseFloat(completionMeterInput.value) || 0;
            const quantity = completionValue - beforeMeterValue;
            const totalPrice = quantity * sellingPrice;
            
            if (completionValue > 0 && quantity >= 0) {
                totalSellingPriceInput.value = 'Rs. ' + totalPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            } else {
                totalSellingPriceInput.value = 'Rs. 0.00';
            }
        }

        // Add event listener to completion meter value
        completionMeterInput.addEventListener('input', calculateTotalSellingPrice);

        // Add customer row
        function addCustomerRow() {
            rowCounter++;
            const row = document.createElement('div');
            row.className = 'card mb-3 customer-row';
            row.dataset.rowId = rowCounter;
            
            row.innerHTML = `
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Customer</label>
                            <select class="form-select customer-select" name="customers[${rowCounter}][customer_id]" required>
                                <option value="">-- Select Customer --</option>
                                ${customers.map(c => `<option value="${c.id}">${c.name} - ${c.phone_number}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity (L)</label>
                            <input type="number" step="0.01" class="form-control quantity-input" 
                                   name="customers[${rowCounter}][quantity]" 
                                   placeholder="0.00" required min="0.01">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price (Rs.)</label>
                            <input type="number" step="0.01" class="form-control price-input" 
                                   name="customers[${rowCounter}][price]" 
                                   placeholder="0.00" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select payment-method-select" name="customers[${rowCounter}][payment_method]" required>
                                <option value="">-- Select Method --</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm w-100 remove-row">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;

            customerRows.appendChild(row);

            // Add event listeners
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');
            const removeBtn = row.querySelector('.remove-row');

            quantityInput.addEventListener('input', function() {
                const quantity = parseFloat(this.value) || 0;
                const price = quantity * sellingPrice;
                priceInput.value = price.toFixed(2);
                calculateTotalSales();
            });

            removeBtn.addEventListener('click', function() {
                row.remove();
                calculateTotalSales();
            });

            // Add Tab key listener on the remove button to add new row
            removeBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !e.shiftKey) {
                    const allRows = document.querySelectorAll('.customer-row');
                    const lastRow = allRows[allRows.length - 1];
                    if (row === lastRow) {
                        e.preventDefault();
                        addCustomerRow();
                        // Focus on the first input of the new row
                        setTimeout(() => {
                            const newRow = document.querySelectorAll('.customer-row')[allRows.length];
                            newRow.querySelector('.customer-select').focus();
                        }, 50);
                    }
                }
            });
        }

        // Calculate total sales from all customer rows
        function calculateTotalSales() {
            let total = 0;
            document.querySelectorAll('.price-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_sales').textContent = 'Rs. ' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // Add first row by default
        addCustomerRow();

        // Calculate on page load if there's an old value
        if (completionMeterInput.value) {
            calculateTotalSellingPrice();
        }
    });
</script>
@endsection
