<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\TransactionService;

beforeEach(function () {
    $this->service = new TransactionService();
});

test('order total is calculated correctly with subtotal and shipping', function () {
    $product = Product::factory()->create(['selling_price' => 100000]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 15000,
        'discount' => 0,
    ];

    $items = [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 100000,
            'quantity' => 2,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->subtotal)->toBe('200000.00')
        ->and($order->shipping_cost)->toBe('15000.00')
        ->and($order->total)->toBe('215000.00');
});

test('order total is calculated correctly with discount', function () {
    $product = Product::factory()->create(['selling_price' => 100000]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 15000,
        'discount' => 20000,
    ];

    $items = [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 100000,
            'quantity' => 2,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->subtotal)->toBe('200000.00')
        ->and($order->shipping_cost)->toBe('15000.00')
        ->and($order->discount)->toBe('20000.00')
        ->and($order->total)->toBe('195000.00');
});

test('order total is calculated correctly with multiple items', function () {
    $product1 = Product::factory()->create(['selling_price' => 100000]);
    $product2 = Product::factory()->create(['selling_price' => 50000]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 15000,
        'discount' => 0,
    ];

    $items = [
        [
            'product_id' => $product1->id,
            'product_name' => $product1->name,
            'price' => 100000,
            'quantity' => 2,
        ],
        [
            'product_id' => $product2->id,
            'product_name' => $product2->name,
            'price' => 50000,
            'quantity' => 3,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->subtotal)->toBe('350000.00')
        ->and($order->total)->toBe('365000.00')
        ->and($order->items)->toHaveCount(2);
});

test('order item subtotal is calculated correctly', function () {
    $product = Product::factory()->create(['selling_price' => 100000]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 15000,
        'discount' => 0,
    ];

    $items = [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 100000,
            'quantity' => 3,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    $orderItem = $order->items->first();

    expect($orderItem->price)->toBe('100000.00')
        ->and($orderItem->quantity)->toBe(3)
        ->and($orderItem->subtotal)->toBe('300000.00');
});

test('order total is recalculated when shipping cost is updated', function () {
    $order = Order::factory()
        ->withTotals(200000, 15000, 0)
        ->create();

    $this->service->updateShipping($order, [
        'shipping_cost' => 25000,
    ]);

    $order->refresh();

    expect($order->subtotal)->toBe('200000.00')
        ->and($order->shipping_cost)->toBe('25000.00')
        ->and($order->total)->toBe('225000.00');
});

test('order total is not changed when only courier is updated', function () {
    $order = Order::factory()
        ->withTotals(200000, 15000, 0)
        ->create();

    $originalTotal = $order->total;

    $this->service->updateShipping($order, [
        'courier' => 'JNE',
        'tracking_number' => 'JNE123456',
    ]);

    $order->refresh();

    expect($order->total)->toBe($originalTotal)
        ->and($order->courier)->toBe('JNE')
        ->and($order->tracking_number)->toBe('JNE123456');
});

test('order with zero shipping cost calculates correctly', function () {
    $product = Product::factory()->create(['selling_price' => 100000]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 0,
        'discount' => 0,
    ];

    $items = [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 100000,
            'quantity' => 1,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->subtotal)->toBe('100000.00')
        ->and($order->shipping_cost)->toBe('0.00')
        ->and($order->total)->toBe('100000.00');
});

test('order with decimal prices calculates correctly', function () {
    $product = Product::factory()->create(['selling_price' => 99999.99]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 12500.50,
        'discount' => 1000.49,
    ];

    $items = [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 99999.99,
            'quantity' => 2,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->subtotal)->toBe('199999.98')
        ->and($order->shipping_cost)->toBe('12500.50')
        ->and($order->discount)->toBe('1000.49')
        ->and($order->total)->toBe('211499.99');
});

test('order subtotal equals sum of all item subtotals', function () {
    $product1 = Product::factory()->create(['selling_price' => 150000]);
    $product2 = Product::factory()->create(['selling_price' => 75000]);
    $product3 = Product::factory()->create(['selling_price' => 200000]);

    $orderData = [
        'customer_name' => 'Test Customer',
        'customer_whatsapp' => '081234567890',
        'customer_email' => 'test@example.com',
        'shipping_address' => 'Test Address',
        'shipping_city' => 'Test City',
        'shipping_province' => 'Test Province',
        'shipping_postal_code' => '12345',
        'shipping_cost' => 20000,
        'discount' => 0,
    ];

    $items = [
        [
            'product_id' => $product1->id,
            'product_name' => $product1->name,
            'price' => 150000,
            'quantity' => 2,
        ],
        [
            'product_id' => $product2->id,
            'product_name' => $product2->name,
            'price' => 75000,
            'quantity' => 4,
        ],
        [
            'product_id' => $product3->id,
            'product_name' => $product3->name,
            'price' => 200000,
            'quantity' => 1,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    $calculatedSubtotal = $order->items->sum('subtotal');

    expect((float)$order->subtotal)->toBe($calculatedSubtotal)
        ->and((float)$order->subtotal)->toBe(800000.0);
});
