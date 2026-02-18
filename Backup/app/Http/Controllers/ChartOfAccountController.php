<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoicePaymentSetoff;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    /**
     * Display the supplier account details with all transactions
     */
    public function show($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        // Get all payments for this supplier with their setoffs
        $payments = Payment::where('supplier_id', $supplierId)
            ->with(['setoffs.invoice', 'bank'])
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // Get all invoices for this supplier with items and setoffs
        $invoices = Invoice::where('supplier_id', $supplierId)
            ->with(['items.item', 'paymentSetoffs.payment'])
            ->orderBy('invoice_date', 'desc')
            ->get();
        
        // Get all payment setoffs for this supplier
        $setoffs = InvoicePaymentSetoff::whereHas('invoice', function($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->with(['payment', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate summary totals
        $totalPayments = $payments->sum('amount');
        $totalInvoices = $invoices->sum('total_amount');
        $totalSetoffs = $setoffs->sum('amount');
        $supplierBalance = $totalPayments - $totalInvoices;
        
        return view('chart-of-account.show', compact(
            'supplier',
            'payments',
            'invoices',
            'setoffs',
            'totalPayments',
            'totalInvoices',
            'totalSetoffs',
            'supplierBalance'
        ));
    }
}
