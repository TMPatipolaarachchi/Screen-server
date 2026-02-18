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
        Schema::create('supplier_balance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['invoice', 'payment', 'invoice_deleted', 'payment_deleted']); // Type of transaction
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->decimal('previous_balance', 15, 2); // Balance before transaction
            $table->decimal('current_balance', 15, 2); // Balance after transaction
            $table->string('reference_type')->nullable(); // Invoice or Payment
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of invoice or payment
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_balance_logs');
    }
};
