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
        // First, update the ENUM column to include all values
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'complete', 'deleted') NOT NULL DEFAULT 'pending'");
        
        // Then convert existing statuses
        DB::statement("UPDATE invoices SET status = 'pending' WHERE status = 'active'");
        DB::statement("UPDATE invoices SET status = 'complete' WHERE status = 'inactive'");
        
        // Finally, remove old values from ENUM
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('pending', 'complete', 'deleted') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values
        DB::statement("UPDATE invoices SET status = 'active' WHERE status = 'pending'");
        DB::statement("UPDATE invoices SET status = 'inactive' WHERE status IN ('complete', 'deleted')");
        
        // Revert ENUM column
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'");
    }
};
