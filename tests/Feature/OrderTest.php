<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('order can be created with required attributes', function () {
    $order = Order::factory()->create([
        'customer_name' => 'John Doe',
        'customer_whatsapp' => '628123456789',
        'total' => 200000,
    ]);

    expect($order->customer_name)->toBe('John Doe')
        ->and($order->customer_whatsapp)->toBe('628123456789')
        ->and($order->total)->toEqual(200000.00);
});

test('order has unique order number', function () {
    $order1 = Order::factory()->create();
    $order2 = Order::factory()->create();

    expect($order1->order_number)->not->toBe($order2->order_number)
        ->and($order1->order_number)->toContain('MHR-');
});

test('order belongs to user', function () {
    $order = Order::factory()->forUser($this->user)->create();

    expect($order->user)->toBeInstanceOf(User::class)
        ->and($order->user->id)->toBe($this->user->id)
        ->and($order->user_id)->toBe($this->user->id);
});

test('order can be for guest customer', function () {
    $order = Order::factory()->forGuest()->create();

    expect($order->user_id)->toBeNull()
        ->and($order->guest_customer_id)->not->toBeNull();
});

test('order has items relationship', function () {
    $order = Order::factory()->create();

    OrderItem::factory()->count(3)->forOrder($order)->create();

    expect($order->items)->toHaveCount(3)
        ->and($order->items->first())->toBeInstanceOf(OrderItem::class);
});

test('order has all status constants', function () {
    expect(Order::STATUS_PENDING)->toBe('pending')
        ->and(Order::STATUS_CONFIRMED)->toBe('confirmed')
        ->and(Order::STATUS_PAYMENT_PENDING)->toBe('payment_pending')
        ->and(Order::STATUS_PAYMENT_CONFIRMED)->toBe('payment_confirmed')
        ->and(Order::STATUS_PROCESSING)->toBe('processing')
        ->and(Order::STATUS_SHIPPED)->toBe('shipped')
        ->and(Order::STATUS_DELIVERED)->toBe('delivered')
        ->and(Order::STATUS_COMPLETED)->toBe('completed')
        ->and(Order::STATUS_CANCELLED)->toBe('cancelled');
});

test('order can get all statuses', function () {
    $statuses = Order::getStatuses();

    expect($statuses)->toBeArray()
        ->and($statuses)->toHaveKey('pending')
        ->and($statuses)->toHaveKey('confirmed')
        ->and($statuses)->toHaveKey('cancelled')
        ->and(count($statuses))->toBeGreaterThan(5);
});

test('order status label returns correct label', function () {
    $order = Order::factory()->pending()->create();

    expect($order->status_label)->toBe('Pending');
});

test('order status color returns correct color', function () {
    $pendingOrder = Order::factory()->pending()->create();
    $cancelledOrder = Order::factory()->cancelled()->create();
    $shippedOrder = Order::factory()->shipped()->create();

    expect($pendingOrder->status_color)->toBe('gray')
        ->and($cancelledOrder->status_color)->toBe('red')
        ->and($shippedOrder->status_color)->toBe('cyan');
});

test('order can be filtered by status', function () {
    Order::factory()->pending()->create();
    Order::factory()->confirmed()->create();
    Order::factory()->confirmed()->create();
    Order::factory()->cancelled()->create();

    $confirmedOrders = Order::status('confirmed')->get();

    expect($confirmedOrders)->toHaveCount(2);
});

test('order can be searched by order number', function () {
    $order = Order::factory()->create(['order_number' => 'MHR-20251221-ABC123']);
    Order::factory()->create();

    $results = Order::search('ABC123')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($order->id);
});

test('order can be searched by customer name', function () {
    $order = Order::factory()->create(['customer_name' => 'Jane Smith']);
    Order::factory()->create(['customer_name' => 'John Doe']);

    $results = Order::search('Jane')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->customer_name)->toBe('Jane Smith');
});

test('order can be searched by customer whatsapp', function () {
    $order = Order::factory()->create(['customer_whatsapp' => '628123456789']);
    Order::factory()->create();

    $results = Order::search('628123456789')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($order->id);
});

test('order recent scope orders by created_at desc', function () {
    $oldOrder = Order::factory()->create(['created_at' => now()->subDays(2)]);
    $newOrder = Order::factory()->create(['created_at' => now()]);

    $orders = Order::recent()->get();

    expect($orders->first()->id)->toBe($newOrder->id)
        ->and($orders->last()->id)->toBe($oldOrder->id);
});

test('order can be soft deleted', function () {
    $order = Order::factory()->create();
    $orderId = $order->id;

    $order->delete();

    expect(Order::find($orderId))->toBeNull()
        ->and(Order::withTrashed()->find($orderId))->not->toBeNull();
});

test('order attributes are cast correctly', function () {
    $order = Order::factory()->create([
        'subtotal' => 100000.50,
        'shipping_cost' => 15000.25,
        'discount' => 5000.00,
        'total' => 110000.75,
    ]);

    expect((float) $order->subtotal)->toBeFloat()
        ->and((float) $order->shipping_cost)->toBeFloat()
        ->and((float) $order->discount)->toBeFloat()
        ->and((float) $order->total)->toBeFloat();
});

test('order can have different statuses', function () {
    $pending = Order::factory()->pending()->create();
    $confirmed = Order::factory()->confirmed()->create();
    $shipped = Order::factory()->shipped()->create();
    $completed = Order::factory()->completed()->create();
    $cancelled = Order::factory()->cancelled()->create();

    expect($pending->status)->toBe(Order::STATUS_PENDING)
        ->and($confirmed->status)->toBe(Order::STATUS_CONFIRMED)
        ->and($shipped->status)->toBe(Order::STATUS_SHIPPED)
        ->and($completed->status)->toBe(Order::STATUS_COMPLETED)
        ->and($cancelled->status)->toBe(Order::STATUS_CANCELLED);
});

test('shipped order has courier and tracking number', function () {
    $order = Order::factory()->shipped()->create();

    expect($order->courier)->not->toBeNull()
        ->and($order->tracking_number)->not->toBeNull();
});
