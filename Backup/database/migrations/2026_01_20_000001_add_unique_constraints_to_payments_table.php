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
        Schema::table('payments', function (Blueprint $table) {
            // Add unique constraint for bank_id and reference_number combination
            $table->unique(['bank_id', 'reference_number'], 'unique_bank_reference');
            
            // Add unique constraint for cheque_number
            $table->unique('cheque_number', 'unique_cheque_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('unique_bank_reference');
            $table->dropUnique('unique_cheque_number');
        });
    }
};
