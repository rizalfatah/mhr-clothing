<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // WhatsApp Settings
            [
                'key' => 'whatsapp_admin_number',
                'value' => '6281234567890',
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Nomor WhatsApp admin (format: 628xxx tanpa +)',
            ],
            [
                'key' => 'whatsapp_admin_name',
                'value' => 'Admin MHR Clothing',
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Nama admin WhatsApp',
            ],

            // Shipping Settings
            [
                'key' => 'shipping_cost',
                'value' => '10000',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Biaya pengiriman default (Rp)',
            ],
            [
                'key' => 'free_shipping_min',
                'value' => '100000',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Minimal belanja untuk gratis ongkir (Rp)',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
