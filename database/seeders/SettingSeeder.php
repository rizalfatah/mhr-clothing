<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'admin@mahrmofficial.com',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Email kontak untuk ditampilkan di header',
            ],
            [
                'key' => 'customer_service_address',
                'value' => '',
                'type' => 'textarea',
                'group' => 'contact',
                'description' => 'Alamat Customer Service untuk ditampilkan di footer',
            ],

            // WhatsApp Settings
            [
                'key' => 'whatsapp_admin_number',
                'value' => '6285867480640', // falih's whatsapp number
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
            [
                'key' => 'whatsapp_message_template',
                'value' => <<<'TEMPLATE'
Halo *{admin_name}*,

Saya ingin melakukan pemesanan dengan detail sebagai berikut:

*Nomor Pesanan: {order_number}*

*Daftar Produk:*
{order_items}

*Ringkasan Pembayaran:*
Subtotal: {subtotal}
{discount_line}Ongkir: {shipping_cost}
━━━━━━━━━━━━━━━
*TOTAL: {total}*

*Informasi Pengiriman:*
Nama: {customer_name}
WhatsApp: {customer_whatsapp}
{customer_email_line}Alamat: {shipping_address}
Kota: {shipping_city}
Provinsi: {shipping_province}
{shipping_postal_code_line}{shipping_notes_line}

Mohon dikonfirmasi. Terima kasih!
TEMPLATE,
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Template pesan WhatsApp untuk pesanan',
            ],
            [
                'key' => 'whatsapp_template_use_header_number',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'whatsapp',
                'description' => 'Gunakan nomor WhatsApp di header sebagai tujuan pesan checkout',
            ],
            [
                'key' => 'whatsapp_template_number',
                'value' => '',
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Nomor WhatsApp tujuan khusus untuk pesan checkout',
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

            // Promotional Settings
            [
                'key' => 'promotional_banner',
                'value' => 'Free Shipping on Orders Over $50',
                'type' => 'text',
                'group' => 'promotional',
                'description' => 'Pesan promosi yang ditampilkan di top bar header',
            ],

            // Homepage Settings
            [
                'key' => 'homepage_banner',
                'value' => '',
                'type' => 'image',
                'group' => 'homepage',
                'description' => 'Banner utama yang ditampilkan di halaman beranda',
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
