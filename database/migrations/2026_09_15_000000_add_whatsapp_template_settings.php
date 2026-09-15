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
        $now = now();

        DB::table('settings')->insertOrIgnore([
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
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'whatsapp_template_use_header_number',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'whatsapp',
                'description' => 'Gunakan nomor WhatsApp di header sebagai tujuan pesan checkout',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'whatsapp_template_number',
                'value' => '',
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Nomor WhatsApp tujuan khusus untuk pesan checkout',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'whatsapp_message_template',
            'whatsapp_template_use_header_number',
            'whatsapp_template_number',
        ])->delete();
    }
};
