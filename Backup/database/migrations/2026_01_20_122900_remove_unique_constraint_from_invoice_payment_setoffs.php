<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoice_payment_setoffs', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['invoice_id']);
            
            // Drop the unique constraint
            $table->dropUnique(['invoice_id', 'payment_id']);
            
            // Re-add the foreign key constraint
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_payment_setoffs', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['invoice_id']);
            
            // Re-add the unique constraint
            $table->unique(['invoice_id', 'payment_id']);
            
            // Re-add the foreign key constraint
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }
};
