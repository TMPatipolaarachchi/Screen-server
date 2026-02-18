@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Create Invoice')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Create Invoice Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #000000 !important;">
                        Create New Invoice
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
                        @csrf

                        <div class="row mb-4">
                            <!-- Invoice Date -->
                            <div class="col-md-4">
                                <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                <input
                                    type="date"
                                    class="form-control @error('invoice_date') is-invalid @enderror"
                                    id="invoice_date"
                                    name="invoice_date"
                                    value="{{ old('invoice_date', date('Y-m-d')) }}"
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
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
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
                                    value="{{ old('invoice_number', $invoiceNumber) }}"
                                    required
                                />
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Vehicle -->
                            <div class="col-md-4">
                                <label for="vehicle_id" class="form-label">Vehicle</label>
                                <select
                                    class="form-select @error('vehicle_id') is-invalid @enderror"
                                    id="vehicle_id"
                                    name="vehicle_id"
                                >
                                    <option value="">-- Select Vehicle --</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                                            {{ $vehicle->vehicle_number }} 
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Driver -->
                            <div class="col-md-4">
                                <label for="employee_id" class="form-label">Driver</label>
                                <select
                                    class="form-select @error('employee_id') is-invalid @enderror"
                                    id="employee_id"
                                    name="employee_id"
                                >
                                    <option value="">-- Select Driver --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                            {{ $employee->name }} 
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Helper -->
                            <div class="col-md-4">
                                <label for="helper_id" class="form-label">Helper</label>
                                <select
                                    class="form-select @error('helper_id') is-invalid @enderror"
                                    id="helper_id"
                                    name="helper_id"
                                >
                                    <option value="">-- Select Helper --</option>
                                    @foreach($helpers as $helper)
                                        <option value="{{ $helper->id }}" @selected(old('helper_id') == $helper->id)>
                                            {{ $helper->name }} 
                                        </option>
                                    @endforeach
                                </select>
                                @error('helper_id')
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
                                        <tr class="item-row">
                                            <td>
                                                <select class="form-select item-select" name="items[0][item_id]" required>
                                                    <option value="">-- Select Item --</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" 
                                                                data-cost-price="{{ $item->cost_price }}"
                                                                data-vat-available="{{ $item->vat_available ? '1' : '0' }}">
                                                            {{ $item->name }} ({{ $item->item_code }})
                                                            @if($item->vat_available)
                                                                <span style="color: green;">✓ VAT</span>
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select tank-select" name="items[0][tank_id]">
                                                    <option value="">-- Select Tank --</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" value="1" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.00001" class="form-control cost-price-input" name="items[0][cost_price]" readonly required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.00001" class="form-control total-input" name="items[0][total]" readonly required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
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
                                            <label for="vat_percentage" class="form-label">VAT (%) - Applied to VAT-available items only</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="vat_percentage" 
                                                   name="vat_percentage" 
                                                   value="{{ $defaultVat ?? 0 }}" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.00001"
                                                   readonly>
                                            <small class="form-text text-muted">
                                                VAT will only be calculated for items marked as "VAT Available"
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mb-3">
                                            <small class="mb-0">VAT Amount:</small>
                                            <small class="mb-0" id="vatAmountDisplay">LKR 0.00</small>
                                        </div>
                                        
                                        <!-- Bank Charge -->
                                        <div class="mb-3">
                                            <label for="bank_charge" class="form-label">Bank Charge (LKR)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="bank_charge" 
                                                   name="bank_charge" 
                                                   value="0" 
                                                   min="0" 
                                                   step="0.01">
                                           
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mb-3">
                                            <small class="mb-0">Bank Charge:</small>
                                            <small class="mb-0" id="bankChargeDisplay">LKR 0.00</small>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check custom-checkbox-wrapper">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="addPayments" name="add_payments" value="1">
                                <label class="form-check-label custom-checkbox-label" for="addPayments">
                                    Add payments for this invoice
                                </label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Create Invoice
                                </button>
                                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    
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
        let totalVatAmount = 0;
        const vatPercentage = parseFloat(document.getElementById('vat_percentage').value) || 0;
        
        // Calculate subtotal and VAT per item
        document.querySelectorAll('.item-row').forEach(row => {
            const itemTotal = parseFloat(row.querySelector('.total-input').value) || 0;
            subtotal += itemTotal;
            
            // Check if this item has VAT available
            const itemSelect = row.querySelector('.item-select');
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const vatAvailable = selectedOption.getAttribute('data-vat-available') === '1';
            
            if (vatAvailable && itemTotal > 0) {
                const itemVat = (itemTotal * vatPercentage) / 100;
                totalVatAmount += itemVat;
            }
        });
        
        const vatAmount = totalVatAmount;
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
                        <option value="{{ $item->id }}" 
                                data-cost-price="{{ $item->cost_price }}"
                                data-vat-available="{{ $item->vat_available ? '1' : '0' }}">
                            {{ $item->name }} ({{ $item->item_code }})
                            @if($item->vat_available)
                                ✓ VAT
                            @endif
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
                    
                    // If we're on the last focusable element (total input)
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
    updateRemoveButtons();
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

    /* Professional Checkbox Styling */
    .custom-checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #f59e0b;
        border: 2px solid #d1d5db;
        border-radius: 4px;
    }

    .custom-checkbox:checked {
        background-color: #f59e0b;
        border-color: #f59e0b;
    }

    .custom-checkbox:hover {
        border-color: #f59e0b;
    }

    .custom-checkbox:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }

    .custom-checkbox-label {
        cursor: pointer;
        user-select: none;
        font-weight: 500;
        color: #000000;
    }
</style>
@endsection
