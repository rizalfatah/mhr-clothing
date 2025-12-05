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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            // Customer Info - bisa dari user atau guest
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('guest_customer_id', 36)->nullable();

            // Contact Info
            $table->string('customer_name');
            $table->string('customer_whatsapp');
            $table->string('customer_email')->nullable();

            // Shipping Address
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_province');
            $table->string('shipping_postal_code');
            $table->text('shipping_notes')->nullable();

            // Order Details
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Status
            $table->enum('status', [
                'pending',           // Baru dibuat, belum kontak WA
                'contacted',         // Sudah dikirim ke WA admin
                'confirmed',         // Admin confirm pesanan
                'payment_pending',   // Menunggu pembayaran
                'payment_confirmed', // Pembayaran diterima
                'processing',        // Sedang diproses/dikemas
                'shipped',           // Sudah dikirim
                'delivered',         // Sudah diterima customer
                'completed',         // Transaksi selesai
                'cancelled'          // Dibatalkan
            ])->default('pending');

            // WhatsApp Integration
            $table->string('whatsapp_message_id')->nullable(); // ID pesan WA yang dikirim
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->text('whatsapp_message')->nullable(); // Template pesan yang dikirim

            // Additional Info
            $table->text('admin_notes')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('courier')->nullable(); // JNE, JNT, SiCepat, etc

            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_number', 'status']);
            $table->index('user_id');
            $table->index('guest_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
