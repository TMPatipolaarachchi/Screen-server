<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class UpdateInvoicePaymentAmountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing invoices with their paid and remaining amounts
        $invoices = Invoice::all();
        
        foreach ($invoices as $invoice) {
            $paidAmount = $invoice->paymentSetoffs()->sum('amount');
            $remainingAmount = $invoice->total_amount - $paidAmount;
            
            $invoice->update([
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
            ]);
        }
        
        $this->command->info('Updated payment amounts for ' . $invoices->count() . ' invoices.');
    }
}
