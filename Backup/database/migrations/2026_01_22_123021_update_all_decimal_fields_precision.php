<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
            $table->decimal('remaining_balance', 15, 2)->change();
        });
        
        // Update invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->default(0)->change();
            $table->decimal('vat_percentage', 8, 2)->default(0)->change();
            $table->decimal('vat_amount', 15, 2)->default(0)->change();
            $table->decimal('total_amount', 15, 2)->default(0)->change();
            $table->decimal('paid_amount', 15, 2)->default(0)->change();
            $table->decimal('remaining_amount', 15, 2)->default(0)->change();
        });
        
        // Update invoice_items table - cost_price keeps 5 decimals, total gets 2
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 5)->change();
            $table->decimal('total', 15, 2)->change();
        });
        
        // Update invoice_payment_setoffs table
        Schema::table('invoice_payment_setoffs', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
        });
        
        // Update supplier_balance_logs table
        Schema::table('supplier_balance_logs', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
            $table->decimal('previous_balance', 15, 2)->change();
            $table->decimal('current_balance', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
            $table->decimal('remaining_balance', 15, 2)->change();
        });
        
        // Revert invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->change();
            $table->decimal('vat_percentage', 8, 2)->change();
            $table->decimal('vat_amount', 15, 2)->change();
            $table->decimal('total_amount', 15, 2)->change();
            $table->decimal('paid_amount', 15, 2)->change();
            $table->decimal('remaining_amount', 15, 2)->change();
        });
        
        // Revert invoice_items table
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });
        
        // Revert invoice_payment_setoffs table
        Schema::table('invoice_payment_setoffs', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
        });
        
        // Revert supplier_balance_logs table
        Schema::table('supplier_balance_logs', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
            $table->decimal('previous_balance', 15, 2)->change();
            $table->decimal('current_balance', 15, 2)->change();
        });
    }
};
