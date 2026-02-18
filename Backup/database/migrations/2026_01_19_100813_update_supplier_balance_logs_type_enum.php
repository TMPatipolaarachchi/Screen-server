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
        DB::statement("ALTER TABLE supplier_balance_logs MODIFY COLUMN type ENUM('invoice', 'payment', 'invoice_deleted', 'payment_deleted') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE supplier_balance_logs MODIFY COLUMN type ENUM('invoice', 'payment') NOT NULL");
    }
};
