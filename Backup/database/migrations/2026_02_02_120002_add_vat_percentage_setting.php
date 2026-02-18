<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add default VAT percentage setting if it doesn't exist
        Setting::firstOrCreate(
            ['key' => 'vat_percentage'],
            ['value' => '0', 'type' => 'number']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'vat_percentage')->delete();
    }
};
