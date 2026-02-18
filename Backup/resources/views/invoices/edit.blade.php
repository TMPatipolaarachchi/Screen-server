@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Edit Invoice')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Edit Invoice Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Edit Invoice
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}" id="invoiceForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <!-- Invoice Date -->
                            <div class="col-md-4">
                                <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                <input
                                    type="date"
                                    class="form-control @error('invoice_date') is-invalid @enderror"
                                    id="invoice_date"
                                    name="invoice_date"
                                    value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                    required
                                />
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select
                                    class="form-select @error('supplier_id') is-invalid @enderror"
                                    id="supplier_id"
                                    name="supplier_id"
                                    required
                                >
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id', $invoice->supplier_id) == $supplier->id)>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Invoice Number -->
                            <div class="col-md-4">
                                <label for="invoice_number" class="form-label">Invoice Number <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control @error('invoice_number') is-invalid @enderror"
                                    id="invoice_number"
                                    name="invoice_number"
                                    value="{{ old('invoice_number', $invoice->invoice_number) }}"
                                    required
                                />
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-4">
                            <h5 class="mb-3">Invoice Items</h5>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead style="position: sticky; top: 0; z-index: 10; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                        <tr>
                                            <th style="width: 30%; color: #000000 !important; font-weight: 600;">Item</th>
                                            <th style="width: 20%; color: #000000 !important; font-weight: 600;">Tank</th>
                                            <th style="width: 12%; color: #000000 !important; font-weight: 600;">Quantity</th>
                                            <th style="width: 15%; color: #000000 !important; font-weight: 600;">Cost Price (LKR)</th>
                                            <th style="width: 18%; color: #000000 !important; font-weight: 600;">Total (LKR)</th>
                                            <th style="width: 5%; color: #000000 !important; font-weight: 600;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        @foreach($invoice->items as $index => $invoiceItem)
                                        <tr class="item-row" data-item-id="{{ $invoiceItem->item_id }}" data-tank-id="{{ $invoiceItem->tank_id }}">
                                            <td>
                                                <select class="form-select item-select" name="items[{{ $index }}][item_id]" required>
                                                    <option value="">-- Select Item --</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" 
                                                                data-cost-price="{{ $item->cost_price }}"
                                                                @selected($invoiceItem->item_id == $item->id)>
                                                            {{ $item->name }} ({{ $item->item_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select tank-select" name="items[{{ $index }}][tank_id]">
                                                    <option value="">-- Select Tank --</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity-input" 
                                                       name="items[{{ $index }}][quantity]" 
                                                       min="1" 
                                                       value="{{ $invoiceItem->quantity }}" 
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.00001" class="form-control cost-price-input" 
                                                       name="items[{{ $index }}][cost_price]" 
                                                       value="{{ $invoiceItem->cost_price }}" 
                                                       readonly 
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.00001" class="form-control total-input" 
                                                       name="items[{{ $index }}][total]" 
                                                       value="{{ $invoiceItem->total }}" 
                                                       readonly 
                                                       required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Totals Section -->
                        <div class="row mb-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Subtotal -->
                                        <div class="d-flex justify-content-between mb-3">
                                            <h6 class="mb-0">Subtotal:</h6>
                                            <h6 class="mb-0" id="subtotalDisplay">LKR 0.00</h6>
                                        </div>
                                        
                                        <!-- VAT -->
                                        <div class="mb-3">
                                            <label for="vat_percentage" class="form-label">VAT (%)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="vat_percentage" 
                                                   name="vat_percentage" 
                                                   value="{{ old('vat_percentage', $invoice->vat_percentage ?? 0) }}" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.00001"
                                                   readonly>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mb-3">
                                            <h6 class="mb-0">VAT Amount:</h6>
                                            <h6 class="mb-0" id="vatAmountDisplay">LKR 0.00</h6>
                                        </div>
                                        
                                        <!-- Bank Charge -->
                                        <div class="mb-3">
                                            <label for="bank_charge" class="form-label">Bank Charge (LKR)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="bank_charge" 
                                                   name="bank_charge" 
                                                   value="{{ old('bank_charge', $invoice->bank_charge ?? 0) }}" 
                                                   min="0" 
                                                   step="0.01">
                                            <small class="text-muted">Additional charges (e.g., processing fee)</small>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mb-3">
                                            <h6 class="mb-0">Bank Charge:</h6>
                                            <h6 class="mb-0" id="bankChargeDisplay">LKR 0.00</h6>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!-- Grand Total -->
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0 fw-bold">Grand Total:</h5>
                                            <h5 class="mb-0 fw-bold text-primary" id="grandTotal">LKR 0.00</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save changes
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
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
    let rowIndex = {{ $invoice->items->count() }};
    
    // Tanks data from PHP
    const tanksData = @json($tanks);

    // Function to populate tanks based on selected item
    function populateTanks(row, itemId, selectedTankId = null) {
        const tankSelect = row.querySelector('.tank-select');
        tankSelect.innerHTML = '<option value="">-- Select Tank --</option>';
        
        const itemTanks = tanksData.filter(tank => tank.item_id == itemId);
        itemTanks.forEach(tank => {
            const option = document.createElement('option');
            option.value = tank.id;
            option.textContent = tank.tank_name;
            if (selectedTankId && tank.id == selectedTankId) {
                option.selected = true;
            }
            tankSelect.appendChild(option);
        });
    }

    // Function to calculate row total
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const costPrice = parseFloat(row.querySelector('.cost-price-input').value) || 0;
        const total = quantity * costPrice;
        row.querySelector('.total-input').value = total.toFixed(5);
        calculateGrandTotal();
    }

    // Function to calculate grand total
    function calculateGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll('.total-input').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        
        const vatPercentage = parseFloat(document.getElementById('vat_percentage').value) || 0;
        const vatAmount = (subtotal * vatPercentage) / 100;
        const bankCharge = parseFloat(document.getElementById('bank_charge').value) || 0;
        const grandTotal = subtotal + vatAmount + bankCharge;
        
        const formattedSubtotal = subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formattedVatAmount = vatAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formattedBankCharge = bankCharge.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formattedGrandTotal = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        document.getElementById('subtotalDisplay').textContent = 'LKR ' + formattedSubtotal;
        document.getElementById('vatAmountDisplay').textContent = 'LKR ' + formattedVatAmount;
        document.getElementById('bankChargeDisplay').textContent = 'LKR ' + formattedBankCharge;
        document.getElementById('grandTotal').textContent = 'LKR ' + formattedGrandTotal;
    }

    // Handle item selection
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-select')) {
            const row = e.target.closest('.item-row');
            const selectedOption = e.target.options[e.target.selectedIndex];
            const costPrice = selectedOption.getAttribute('data-cost-price') || 0;
            const itemId = e.target.value;
            
            row.querySelector('.cost-price-input').value = parseFloat(costPrice).toFixed(5);
            
            // Populate tanks for selected item
            if (itemId) {
                populateTanks(row, itemId);
            } else {
                row.querySelector('.tank-select').innerHTML = '<option value="">-- Select Tank --</option>';
            }
            
            calculateRowTotal(row);
        }
    });

    // Handle quantity change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.item-row');
            calculateRowTotal(row);
        }
    });

    // Handle VAT percentage change
    document.getElementById('vat_percentage').addEventListener('input', function() {
        calculateGrandTotal();
    });

    // Handle Bank Charge change
    document.getElementById('bank_charge').addEventListener('input', function() {
        calculateGrandTotal();
    });

    // Function to add new row
    function addNewRow() {
        const tableBody = document.getElementById('itemsTableBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select class="form-select item-select" name="items[${rowIndex}][item_id]" required>
                    <option value="">-- Select Item --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-cost-price="{{ $item->cost_price }}">
                            {{ $item->name }} ({{ $item->item_code }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-select tank-select" name="items[${rowIndex}][tank_id]">
                    <option value="">-- Select Tank --</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="items[${rowIndex}][quantity]" min="1" value="1" required>
            </td>
            <td>
                <input type="number" step="0.00001" class="form-control cost-price-input" name="items[${rowIndex}][cost_price]" readonly required>
            </td>
            <td>
                <input type="number" step="0.00001" class="form-control total-input" name="items[${rowIndex}][total]" readonly required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        rowIndex++;
        updateRemoveButtons();
        
        // Focus on the first input of the new row
        newRow.querySelector('.item-select').focus();
    }

    // Handle Tab key press to add new row
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && !e.shiftKey) {
            const activeElement = document.activeElement;
            
            // Check if we're in the items table
            if (activeElement && activeElement.closest('#itemsTableBody')) {
                const currentRow = activeElement.closest('.item-row');
                const allRows = document.querySelectorAll('.item-row');
                const lastRow = allRows[allRows.length - 1];
                
                // Check if we're in the last row
                if (currentRow === lastRow) {
                    // Get all focusable elements in the current row
                    const focusableElements = currentRow.querySelectorAll('select, input:not([readonly]), button');
                    const lastFocusable = focusableElements[focusableElements.length - 2]; // -2 to exclude remove button
                    
                    // If we're on the last focusable element (quantity input)
                    if (activeElement === lastFocusable) {
                        e.preventDefault();
                        addNewRow();
                    }
                }
            }
        }
    });

    // Remove row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('.item-row');
            row.remove();
            calculateGrandTotal();
            updateRemoveButtons();
        }
    });

    // Update remove buttons (disable if only one row)
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        const removeButtons = document.querySelectorAll('.remove-row');
        removeButtons.forEach(btn => {
            btn.disabled = rows.length === 1;
        });
    }

    // Form validation
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.item-row');
        let isValid = true;

        rows.forEach(row => {
            const itemSelect = row.querySelector('.item-select');
            const quantity = row.querySelector('.quantity-input');
            const costPrice = row.querySelector('.cost-price-input');

            if (!itemSelect.value || !quantity.value || !costPrice.value) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all item details before submitting.');
            return false;
        }

        if (rows.length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the invoice.');
            return false;
        }
    });

    // Initialize
    // Populate tanks for existing rows
    document.querySelectorAll('.item-row').forEach(row => {
        const itemId = row.getAttribute('data-item-id');
        const tankId = row.getAttribute('data-tank-id');
        if (itemId) {
            populateTanks(row, itemId, tankId);
        }
    });
    
    updateRemoveButtons();
    calculateGrandTotal();
});
</script>

<style>
    #itemsTable {
        border-collapse: separate;
        border-spacing: 0;
    }

    #itemsTable thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    #itemsTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .item-select,
    .tank-select,
    .quantity-input,
    .cost-price-input,
    .total-input {
        border: 1px solid #ced4da;
    }

    .item-select:focus,
    .tank-select:focus,
    .quantity-input:focus,
    .cost-price-input:focus,
    .total-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endsection
