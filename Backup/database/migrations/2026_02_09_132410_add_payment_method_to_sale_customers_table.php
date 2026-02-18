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
        Schema::table('sale_customers', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'cheque', 'online'])->default('cash')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_customers', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
