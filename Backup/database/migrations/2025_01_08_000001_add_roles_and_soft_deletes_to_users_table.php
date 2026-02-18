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
        Schema::table('users', function (Blueprint $table) {
            // Add role column with default 'user'
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
            
            // Add phone and NIC number columns
            $table->string('phone')->nullable()->unique()->after('role');
            $table->string('nic_number')->nullable()->unique()->after('phone');
            
            // Add soft deletes for user deletion
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('phone');
            $table->dropColumn('nic_number');
            $table->dropSoftDeletes();
        });
    }
};
