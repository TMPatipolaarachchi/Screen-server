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
        // First, add status column if it doesn't exist
        if (!Schema::hasColumn('payments', 'status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('status', ['pending', 'complete', 'deleted'])->default('pending')->after('amount');
            });
        } else {
            // If column exists, modify the status enum to include our new values
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'complete', 'deleted') NOT NULL DEFAULT 'pending'");
        }
        
        // Update existing records to set proper status values
        // Update deleted records (where deleted_at is not null)
        DB::statement("
            UPDATE payments 
            SET status = 'deleted'
            WHERE deleted_at IS NOT NULL
        ");
        
        // Update records based on remaining_balance for non-deleted records
        DB::statement("
            UPDATE payments 
            SET status = CASE 
                WHEN remaining_balance <= 0 THEN 'complete'
                ELSE 'pending'
            END
            WHERE deleted_at IS NULL
        ");
        
        // Now modify the status enum to only include our new values
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'complete', 'deleted') NOT NULL DEFAULT 'pending'");
        
        // Remove soft deletes column since we're using status now
        Schema::table('payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add soft deletes
        Schema::table('payments', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        // Restore deleted_at for records with status 'deleted'
        DB::statement("
            UPDATE payments 
            SET deleted_at = updated_at
            WHERE status = 'deleted'
        ");
        
        // Restore original enum values
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'");
    }
};