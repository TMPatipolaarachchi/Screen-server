@extends('layouts.app')

@section('body_class', 'page-chart-of-account')

@section('title', 'Chart of Account - ' . $supplier->name)

@section('content')
<div class="container-fluid mt-4 mb-5" style="position: relative; z-index: 1;">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-black mb-2">
                        Chart of Account
                    </h2>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <p class="text-black mb-0">
                            <i class="fas fa-user-tie me-2"></i>
                            <strong>Supplier:</strong> {{ $supplier->name }}
                        </p>
                        @if($supplier->contact_person)
                            <p class="text-black mb-0">
                                <i class="fas fa-user me-2"></i>
                                <strong>Contact:</strong> {{ $supplier->contact_person }}
                            </p>
                        @endif
                        @if($supplier->phone)
                            <p class="text-black mb-0">
                                <i class="fas fa-phone me-2"></i>
                                <strong>Phone:</strong> {{ $supplier->phone }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards Row -->
    <div class="row mb-4">
        <!-- Total Payments Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-hand-holding-usd text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Payments</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold  mb-2">Rs. {{ number_format($totalPayments, 2, '.', ',') }}</h3>
                    <small class="text-muted">
                        <i class="fas fa-receipt me-1"></i>
                        {{ $payments->count() }} payments
                    </small>
                </div>
            </div>
        </div>

        <!-- Total Invoices Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-file-invoice text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Invoices</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold  mb-2">Rs. {{ number_format($totalInvoices, 2, '.', ',') }}</h3>
                    <small class="text-muted">
                        <i class="fas fa-file-alt me-1"></i>
                        {{ $invoices->count() }} invoices
                    </small>
                </div>
            </div>
        </div>

        <!-- Supplier Balance Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-{{ $supplierBalance >= 0 ? 'primary' : 'warning' }} bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-wallet text-{{ $supplierBalance >= 0 ? 'primary' : 'warning' }} fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Balance</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2">
                        {{ $totalInvoices > $totalPayments ? '-' : '+' }} Rs. {{ number_format(abs($supplierBalance), 2, '.', ',') }}
                    </h3>
                    <small class="text-muted">
                        <i class="fas fa-{{ $supplierBalance >= 0 ? 'check-circle' : 'exclamation-triangle' }} me-1"></i>
                        @if($supplierBalance >= 0)
                            Credit Balance
                        @else
                            Outstanding
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Filtered Balance Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-info bg-opacity-10 p-3 rounded-circle me-3" id="filtered-balance-icon">
                            <i class="fas fa-calendar-alt text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">On Balance</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2" id="filtered-balance-amount">
                        Rs. 0.00
                    </h3>
                    <small class="text-muted" id="filtered-balance-status">
                        <i class="fas fa-filter me-1"></i>
                        Current Month
                    </small>
                </div>
            </div>
        </div>

    <!-- Date Filter Section -->
    <div class="row mt-4 mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0 fw-bold text-black">
                                <i class="fas fa-filter me-2"></i>Transaction Filters
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label for="start_date" class="form-label text-muted small mb-1">From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="start_date" value="{{ date('Y-m-01') }}">
                                </div>
                                <div class="col-md-5">
                                    <label for="end_date" class="form-label text-muted small mb-1">To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="end_date" value="{{ date('Y-m-t') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-muted small mb-1">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-secondary btn-sm d-block w-100" id="reset_filter" title="Reset to Current Month">
                                        <i class="fas fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Quick Report Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-black">
                        Account Quick Report
                        <small class="text-muted fs-6 ms-2" id="transaction-count">({{ $invoices->count() + $payments->count() }} transactions)</small>
                    </h5>
                </div>

                <div class="card-body">
                    @php
                        // Combine and sort all transactions by date and time (using created_at for time ordering)
                        $allTransactions = collect();

                        // Add invoices
                        foreach($invoices as $invoice) {
                            $allTransactions->push([
                                'type' => 'invoice',
                                'date' => $invoice->invoice_date,
                                'created_at' => $invoice->created_at ?? $invoice->invoice_date, // fallback if created_at missing
                                'amount' => $invoice->total_amount,
                                'data' => $invoice
                            ]);
                        }

                        // Add payments
                        foreach($payments as $payment) {
                            $allTransactions->push([
                                'type' => 'payment',
                                'date' => $payment->payment_date,
                                'created_at' => $payment->created_at ?? $payment->payment_date, // fallback if created_at missing
                                'amount' => $payment->amount,
                                'data' => $payment
                            ]);
                        }

                        // Sort by date (newest first)
                        $allTransactions = $allTransactions->sortByDesc(function($t) {
                            return \Carbon\Carbon::parse($t['date'])->timestamp;
                        });

                        // Calculate running balance
                        $runningBalance = 0;
                    @endphp

                    @if($allTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="transactions-table">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Split</th>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center">Supplier Balance</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allTransactions as $transaction)
                                        @php
                                            if($transaction['type'] === 'invoice') {
                                                $runningBalance += $transaction['amount']; // Invoice increases balance
                                            } else {
                                                $runningBalance -= $transaction['amount']; // Payment decreases balance
                                            }
                                        @endphp
                                        <tr class="transaction-row text-center" data-date="{{ $transaction['date'] }}" data-type="{{ $transaction['type'] }}" data-amount="{{ $transaction['amount'] }}">
                                            <td class="text-center">
                                                @if($transaction['type'] === 'invoice')
                                                    <span class="text-danger">
                                                        <i class="fas fa-file-invoice me-1"></i>Invoice
                                                    </span>
                                                @else
                                                    <span class="text-success">
                                                        <i class="fas fa-hand-holding-usd me-1"></i>Payment
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                @if($transaction['type'] === 'invoice')
                                                    <span class="fw-bold">
                                                        Invoice #{{ $transaction['data']->invoice_number }}
                                                    </span>
                                                @else
                                                    @if($transaction['data']->payment_method === 'online')
                                                        <small>
                                                            <strong>Bank:</strong> {{ $transaction['data']->bank->name }}<br>
                                                            <strong>Acc:</strong> {{ $transaction['data']->bank->account }}<br>
                                                            <strong>Ref #:</strong> {{ $transaction['data']->reference_number }}
                                                        </small>
                                                    @else
                                                        <small>
                                                            <strong>Cheque #:</strong> {{ $transaction['data']->cheque_number }}
                                                        </small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($transaction['type'] === 'payment')
                                                    <span class="text-success fw-bold">
                                                        Rs. {{ number_format($transaction['amount'], 2, '.', ',') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($transaction['type'] === 'invoice')
                                                    <span class="text-danger fw-bold">
                                                        Rs. {{ number_format($transaction['amount'], 2, '.', ',') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">
                                                    Rs. {{ number_format(abs($runningBalance), 2, '.', ',') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $returnUrl = urlencode(request()->fullUrl());
                                                @endphp
                                                @if($transaction['type'] === 'invoice')
                                                    <a href="{{ route('invoices.show', $transaction['data']->id) }}?return={{ $returnUrl }}" class="btn btn-sm btn-outline-primary" title="View Invoice">
                                                        <i class="fas fa-eye me-1"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('payments.show', $transaction['data']->id) }}?return={{ $returnUrl }}" class="btn btn-sm btn-outline-primary" title="View Payment">
                                                        <i class="fas fa-eye me-1"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- No Results Message (initially hidden) -->
                        <div id="no-results" class="alert alert-info" role="alert" style="display: none;">
                            <i class="fas fa-info-circle"></i> No transactions found for the selected date range.
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> No transaction records available for this supplier.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const resetFilterBtn = document.getElementById('reset_filter');
    const transactionRows = document.querySelectorAll('.transaction-row');
    const transactionTable = document.getElementById('transactions-table');
    const noResultsDiv = document.getElementById('no-results');
    const transactionCount = document.getElementById('transaction-count');
    const filteredBalanceAmount = document.getElementById('filtered-balance-amount');
    const filteredBalanceStatus = document.getElementById('filtered-balance-status');
    const filteredBalanceIcon = document.getElementById('filtered-balance-icon');

    // Function to filter transactions and calculate balance
    function filterTransactions() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        let visibleCount = 0;
        let filteredBalance = 0;

        // Set end date to end of day for proper comparison
        endDate.setHours(23, 59, 59, 999);

        transactionRows.forEach(function(row) {
            const rowDate = new Date(row.getAttribute('data-date'));
            
            if (rowDate >= startDate && rowDate <= endDate) {
                row.style.display = '';
                visibleCount++;
                
                // Calculate filtered balance using data attributes
                const type = row.getAttribute('data-type');
                const amount = parseFloat(row.getAttribute('data-amount'));
                
                if (type === 'invoice') {
                    filteredBalance += amount; // Invoice increases balance (what we owe supplier)
                } else {
                    filteredBalance -= amount; // Payment decreases balance (reduces what we owe)
                }
            } else {
                row.style.display = 'none';
            }
        });

        // Update transaction count
        transactionCount.textContent = `(${visibleCount} transactions)`;

        // Update filtered balance card
        updateFilteredBalanceCard(filteredBalance, visibleCount);

        // Show/hide no results message
        if (visibleCount === 0) {
            transactionTable.style.display = 'none';
            noResultsDiv.style.display = 'block';
        } else {
            transactionTable.style.display = 'table';
            noResultsDiv.style.display = 'none';
        }
    }

    // Function to update filtered balance card
    function updateFilteredBalanceCard(balance, transactionCount) {
        // Format the balance display - positive balance means we owe supplier, negative means supplier owes us
        const formattedBalance = 'Rs. ' + Math.abs(balance).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        filteredBalanceAmount.textContent = (balance > 0 ? '-' : '+') + formattedBalance;

        // Update icon and color based on balance
        const iconContainer = filteredBalanceIcon;
        const icon = filteredBalanceIcon.querySelector('i');
        
        if (balance > 0) {
            // Positive balance means we owe supplier money (outstanding)
            iconContainer.className = 'icon-circle bg-warning bg-opacity-10 p-3 rounded-circle me-3';
            icon.className = 'fas fa-exclamation-triangle text-warning fs-4';
        } else {
            // Negative balance means supplier owes us money or we have credit
            iconContainer.className = 'icon-circle bg-success bg-opacity-10 p-3 rounded-circle me-3';
            icon.className = 'fas fa-check-circle text-success fs-4';
        }

        // Show period info
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const now = new Date();
        const isCurrentMonth = startDate.getMonth() === now.getMonth() && 
                              startDate.getFullYear() === now.getFullYear() &&
                              endDate.getMonth() === now.getMonth() && 
                              endDate.getFullYear() === now.getFullYear();
        
        if (isCurrentMonth) {
            filteredBalanceStatus.innerHTML = '<i class="fas fa-calendar me-1"></i>Current Month (' + transactionCount + ' transactions)';
        } else {
            const options = { month: 'short', year: 'numeric' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', options);
            
            if (startStr === endStr) {
                filteredBalanceStatus.innerHTML = '<i class="fas fa-calendar me-1"></i>' + startStr + ' (' + transactionCount + ' transactions)';
            } else {
                filteredBalanceStatus.innerHTML = '<i class="fas fa-calendar me-1"></i>' + startStr + ' - ' + endStr + ' (' + transactionCount + ' transactions)';
            }
        }
    }

    // Function to reset to current month
    function resetToCurrentMonth() {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        
        startDateInput.value = firstDay.toISOString().split('T')[0];
        endDateInput.value = lastDay.toISOString().split('T')[0];
        
        filterTransactions();
    }

    // Event listeners
    startDateInput.addEventListener('change', filterTransactions);
    endDateInput.addEventListener('change', filterTransactions);
    resetFilterBtn.addEventListener('click', resetToCurrentMonth);

    // Initial filter on page load (shows current month by default)
    filterTransactions();
});
</script>

@endsection
