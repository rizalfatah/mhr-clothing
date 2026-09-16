<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insertOrIgnore([
            'key' => 'customer_service_address',
            'value' => '',
            'type' => 'textarea',
            'group' => 'contact',
            'description' => 'Alamat Customer Service untuk ditampilkan di footer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'customer_service_address')
            ->delete();
    }
};
