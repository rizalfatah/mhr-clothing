<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;

beforeEach(function () {
    $this->order = Order::factory()->create([
        'order_number' => 'MHR-TEST-001',
        'customer_name' => 'Rizal',
        'customer_whatsapp' => '628123456789',
        'customer_email' => 'rizal@example.com',
        'shipping_address' => 'Jl. Merdeka No. 1',
        'shipping_city' => 'Jakarta',
        'shipping_province' => 'DKI Jakarta',
        'shipping_postal_code' => '10110',
        'shipping_notes' => 'Tolong dibungkus rapi',
        'subtotal' => 100000,
        'shipping_cost' => 10000,
        'discount' => 5000,
        'total' => 105000,
    ]);

    OrderItem::factory()->forOrder($this->order)->create([
        'product_name' => 'Kaos MHR',
        'variant_name' => 'L',
        'price' => 100000,
        'quantity' => 1,
        'subtotal' => 100000,
    ]);
});

function setWhatsAppSetting(string $key, string $value, string $type = 'text'): void
{
    Setting::updateOrCreate(
        ['key' => $key],
        [
            'value' => $value,
            'type' => $type,
            'group' => 'whatsapp',
            'description' => $key,
        ]
    );

    Setting::clearCache();
}

test('checkout uses the header number and replaces WhatsApp template placeholders', function () {
    setWhatsAppSetting('whatsapp_admin_number', '6281111111111');
    setWhatsAppSetting('whatsapp_template_use_header_number', '1', 'boolean');
    setWhatsAppSetting('whatsapp_template_number', '6282222222222');
    setWhatsAppSetting('whatsapp_message_template', 'Halo {admin_name}. Order {order_number} untuk {customer_name}: {order_items} Total {total}.');
    setWhatsAppSetting('whatsapp_admin_name', 'Admin MHR');

    $response = $this->get(route('checkout.success', $this->order->uuid));

    $response->assertOk();
    $whatsappUrl = $response->viewData('whatsappUrl');

    expect($whatsappUrl)->toStartWith('https://wa.me/6281111111111?text=');
    expect(urldecode(strstr($whatsappUrl, '?text=')))->toContain('Order MHR-TEST-001 untuk Rizal')
        ->toContain('Kaos MHR')
        ->toContain('Total Rp 105.000.');
});

test('checkout uses the dedicated WhatsApp number when header number mode is disabled', function () {
    setWhatsAppSetting('whatsapp_admin_number', '6281111111111');
    setWhatsAppSetting('whatsapp_template_use_header_number', '0', 'boolean');
    setWhatsAppSetting('whatsapp_template_number', '6282222222222');
    setWhatsAppSetting('whatsapp_message_template', 'Order {order_number}');

    $response = $this->get(route('checkout.success', $this->order->uuid));

    $response->assertOk();
    expect($response->viewData('whatsappUrl'))->toStartWith('https://wa.me/6282222222222?text=');
});
