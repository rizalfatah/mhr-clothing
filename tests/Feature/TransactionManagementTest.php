<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\TransactionService;

beforeEach(function () {
    $this->service = new TransactionService();
});

test('order is created with unique order number', function () {
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
            'quantity' => 1,
        ],
    ];

    $order1 = $this->service->createOrder($orderData, $items);
    $order2 = $this->service->createOrder($orderData, $items);

    expect($order1->order_number)->not->toBe($order2->order_number)
        ->and($order1->order_number)->toStartWith('MHR-')
        ->and($order2->order_number)->toStartWith('MHR-');
});

test('order is created with pending status', function () {
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
            'quantity' => 1,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->status)->toBe(Order::STATUS_PENDING);
});

test('order status can be updated', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    $updatedOrder = $this->service->updateStatus($order, Order::STATUS_CONFIRMED);

    expect($updatedOrder->status)->toBe(Order::STATUS_CONFIRMED);
});

test('order status update can include notes', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    $updatedOrder = $this->service->updateStatus($order, Order::STATUS_CONFIRMED, 'Customer confirmed via WhatsApp');

    expect($updatedOrder->status)->toBe(Order::STATUS_CONFIRMED)
        ->and($updatedOrder->admin_notes)->toBe('Customer confirmed via WhatsApp');
});

test('order can be cancelled', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    $cancelledOrder = $this->service->cancelOrder($order, 'Customer requested cancellation');

    expect($cancelledOrder->status)->toBe(Order::STATUS_CANCELLED)
        ->and($cancelledOrder->admin_notes)->toBe('Customer requested cancellation');
});

test('order can be marked as shipped', function () {
    $order = Order::factory()->confirmed()->create();

    $shippedOrder = $this->service->markAsShipped($order, 'JNE', 'JNE123456789');

    expect($shippedOrder->status)->toBe(Order::STATUS_SHIPPED)
        ->and($shippedOrder->courier)->toBe('JNE')
        ->and($shippedOrder->tracking_number)->toBe('JNE123456789');
});

test('order can be marked as completed', function () {
    $order = Order::factory()->shipped()->create();

    $completedOrder = $this->service->markAsCompleted($order);

    expect($completedOrder->status)->toBe(Order::STATUS_COMPLETED);
});

test('order customer information can be updated', function () {
    $order = Order::factory()->create([
        'customer_name' => 'Old Name',
        'customer_email' => 'old@example.com',
    ]);

    $updatedOrder = $this->service->updateCustomerInfo($order, [
        'customer_name' => 'New Name',
        'customer_email' => 'new@example.com',
        'customer_whatsapp' => '089876543210',
    ]);

    expect($updatedOrder->customer_name)->toBe('New Name')
        ->and($updatedOrder->customer_email)->toBe('new@example.com')
        ->and($updatedOrder->customer_whatsapp)->toBe('089876543210');
});

test('order shipping information can be updated', function () {
    $order = Order::factory()->create();

    $updatedOrder = $this->service->updateShipping($order, [
        'courier' => 'JNE',
        'tracking_number' => 'JNE987654321',
        'shipping_cost' => 20000,
    ]);

    expect($updatedOrder->courier)->toBe('JNE')
        ->and($updatedOrder->tracking_number)->toBe('JNE987654321')
        ->and((float)$updatedOrder->shipping_cost)->toBe(20000.0);
});

test('order notes can be updated', function () {
    $order = Order::factory()->create();

    $updatedOrder = $this->service->updateNotes($order, 'Special handling required');

    expect($updatedOrder->admin_notes)->toBe('Special handling required');
});

test('orders can be filtered by status', function () {
    Order::factory()->count(3)->create(['status' => Order::STATUS_PENDING]);
    Order::factory()->count(2)->create(['status' => Order::STATUS_CONFIRMED]);
    Order::factory()->count(1)->create(['status' => Order::STATUS_SHIPPED]);

    $pendingOrders = $this->service->getOrders(['status' => Order::STATUS_PENDING]);

    expect($pendingOrders->total())->toBe(3);
});

test('orders can be searched by order number', function () {
    $order1 = Order::factory()->create(['order_number' => 'MHR-20250101-ABC123']);
    $order2 = Order::factory()->create(['order_number' => 'MHR-20250101-XYZ789']);

    $results = $this->service->getOrders(['search' => 'ABC123']);

    expect($results->total())->toBe(1)
        ->and($results->first()->order_number)->toBe('MHR-20250101-ABC123');
});

test('orders can be searched by customer name', function () {
    Order::factory()->create(['customer_name' => 'John Doe']);
    Order::factory()->create(['customer_name' => 'Jane Smith']);

    $results = $this->service->getOrders(['search' => 'John']);

    expect($results->total())->toBe(1)
        ->and($results->first()->customer_name)->toBe('John Doe');
});

test('orders can be searched by customer email', function () {
    Order::factory()->create(['customer_email' => 'john@example.com']);
    Order::factory()->create(['customer_email' => 'jane@example.com']);

    $results = $this->service->getOrders(['search' => 'john@example.com']);

    expect($results->total())->toBe(1)
        ->and($results->first()->customer_email)->toBe('john@example.com');
});

test('orders can be searched by customer whatsapp', function () {
    Order::factory()->create(['customer_whatsapp' => '081234567890']);
    Order::factory()->create(['customer_whatsapp' => '089876543210']);

    $results = $this->service->getOrders(['search' => '081234567890']);

    expect($results->total())->toBe(1)
        ->and($results->first()->customer_whatsapp)->toBe('081234567890');
});

test('orders can be filtered by date range', function () {
    Order::factory()->create(['created_at' => '2025-01-01']);
    Order::factory()->create(['created_at' => '2025-01-15']);
    Order::factory()->create(['created_at' => '2025-01-31']);

    $results = $this->service->getOrders([
        'date_from' => '2025-01-10',
        'date_to' => '2025-01-20',
    ]);

    expect($results->total())->toBe(1);
});

test('orders are returned in recent order by default', function () {
    $order1 = Order::factory()->create(['created_at' => now()->subDays(3)]);
    $order2 = Order::factory()->create(['created_at' => now()->subDays(1)]);
    $order3 = Order::factory()->create(['created_at' => now()->subDays(2)]);

    $results = $this->service->getOrders();

    expect($results->first()->id)->toBe($order2->id)
        ->and($results->items()[1]->id)->toBe($order3->id)
        ->and($results->items()[2]->id)->toBe($order1->id);
});

test('statistics returns correct total orders count', function () {
    Order::factory()->count(5)->create();

    $stats = $this->service->getStatistics();

    expect($stats['total_orders'])->toBe(5);
});

test('statistics returns correct pending orders count', function () {
    Order::factory()->count(3)->create(['status' => Order::STATUS_PENDING]);
    Order::factory()->count(2)->create(['status' => Order::STATUS_CONFIRMED]);

    $stats = $this->service->getStatistics();

    expect($stats['pending_orders'])->toBe(3);
});

test('statistics returns correct confirmed orders count', function () {
    Order::factory()->count(2)->create(['status' => Order::STATUS_CONFIRMED]);
    Order::factory()->count(3)->create(['status' => Order::STATUS_PENDING]);

    $stats = $this->service->getStatistics();

    expect($stats['confirmed_orders'])->toBe(2);
});

test('statistics returns correct completed orders count', function () {
    Order::factory()->count(4)->create(['status' => Order::STATUS_COMPLETED]);
    Order::factory()->count(1)->create(['status' => Order::STATUS_PENDING]);

    $stats = $this->service->getStatistics();

    expect($stats['completed_orders'])->toBe(4);
});

test('statistics calculates total revenue correctly', function () {
    Order::factory()->create(['status' => Order::STATUS_COMPLETED, 'total' => 100000]);
    Order::factory()->create(['status' => Order::STATUS_SHIPPED, 'total' => 150000]);
    Order::factory()->create(['status' => Order::STATUS_PENDING, 'total' => 200000]); // Not counted
    Order::factory()->create(['status' => Order::STATUS_PAYMENT_CONFIRMED, 'total' => 50000]);

    $stats = $this->service->getStatistics();

    expect((float)$stats['total_revenue'])->toBe(300000.0);
});

test('statistics returns today orders count', function () {
    Order::factory()->count(3)->create(['created_at' => now()]);
    Order::factory()->count(2)->create(['created_at' => now()->subDays(1)]);

    $stats = $this->service->getStatistics();

    expect($stats['today_orders'])->toBe(3);
});

test('statistics calculates today revenue correctly', function () {
    Order::factory()->create([
        'status' => Order::STATUS_COMPLETED,
        'total' => 100000,
        'created_at' => now(),
    ]);
    Order::factory()->create([
        'status' => Order::STATUS_SHIPPED,
        'total' => 50000,
        'created_at' => now(),
    ]);
    Order::factory()->create([
        'status' => Order::STATUS_PENDING,
        'total' => 200000,
        'created_at' => now(),
    ]); // Not counted

    $stats = $this->service->getStatistics();

    expect((float)$stats['today_revenue'])->toBe(150000.0);
});

test('recent orders can be retrieved with limit', function () {
    Order::factory()->count(15)->create();

    $recentOrders = $this->service->getRecentOrders(5);

    expect($recentOrders)->toHaveCount(5);
});

test('order can be retrieved by order number', function () {
    $order = Order::factory()->create(['order_number' => 'MHR-20250101-TEST123']);

    $foundOrder = $this->service->getOrderByNumber('MHR-20250101-TEST123');

    expect($foundOrder)->not->toBeNull()
        ->and($foundOrder->id)->toBe($order->id);
});

test('order can be soft deleted', function () {
    $order = Order::factory()->create();

    $result = $this->service->deleteOrder($order);

    expect($result)->toBeTrue()
        ->and(Order::withTrashed()->find($order->id)->trashed())->toBeTrue();
});

test('order creation rolls back on failure', function () {
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

    // Invalid items - missing required fields
    $items = [
        [
            'product_id' => $product->id,
            // Missing product_name, price, quantity
        ],
    ];

    try {
        $this->service->createOrder($orderData, $items);
    } catch (\Exception $e) {
        // Expected to fail
    }

    // Verify no order was created
    expect(Order::count())->toBe(0);
})->throws(\Exception::class);

test('order with user relationship is created correctly', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['selling_price' => 100000]);

    $orderData = [
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'customer_whatsapp' => '081234567890',
        'customer_email' => $user->email,
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
            'quantity' => 1,
        ],
    ];

    $order = $this->service->createOrder($orderData, $items);

    expect($order->user_id)->toBe($user->id)
        ->and($order->user->id)->toBe($user->id);
});
