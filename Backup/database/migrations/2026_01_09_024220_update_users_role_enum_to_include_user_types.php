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
        // Step 1: Change enum to VARCHAR temporarily to allow any value
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user1'");
        
        // Step 2: Update all existing 'user' roles to 'user1'
        DB::table('users')
            ->where('role', 'user')
            ->update(['role' => 'user1']);
        
        // Step 3: Change back to ENUM with new values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user1', 'user2', 'user3') NOT NULL DEFAULT 'user1'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Change enum to VARCHAR temporarily
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'");
        
        // Step 2: Update all 'user1', 'user2', 'user3' roles back to 'user'
        DB::table('users')
            ->whereIn('role', ['user1', 'user2', 'user3'])
            ->update(['role' => 'user']);

        // Step 3: Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
