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
        Schema::table('stock_logs', function (Blueprint $table) {
            // Rename reduce_quantity to quantity_change to handle both increase and decrease
            $table->renameColumn('reduce_quantity', 'quantity_change');
            // Add type column to distinguish between increase and decrease
            $table->enum('type', ['increase', 'decrease'])->after('item_id');
            // Add reference type and ID for tracking source (invoice, manual reduction, etc.)
            $table->string('reference_type')->nullable()->after('reason');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->renameColumn('quantity_change', 'reduce_quantity');
            $table->dropColumn(['type', 'reference_type', 'reference_id']);
        });
    }
};
