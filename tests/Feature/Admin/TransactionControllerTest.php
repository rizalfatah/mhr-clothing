<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\TransactionService;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'email' => 'admin@test.com',
        'role' => 'admin',
    ]);

    $this->customer = User::factory()->create([
        'email' => 'customer@test.com',
        'role' => 'customer',
    ]);
});

test('admin can access transactions listing page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index'));

    $response->assertStatus(200)
        ->assertViewIs('admin.transactions.index')
        ->assertViewHas('orders')
        ->assertViewHas('statistics')
        ->assertViewHas('statuses');
});

test('non-admin cannot access transactions page', function () {
    $response = $this->actingAs($this->customer)->get(route('admin.transactions.index'));

    $response->assertStatus(403);
});

test('guest cannot access transactions page', function () {
    $response = $this->get(route('admin.transactions.index'));

    $response->assertRedirect(route('login'));
});

test('transactions listing displays orders', function () {
    Order::factory()->count(5)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index'));

    $orders = $response->viewData('orders');
    expect($orders->total())->toBe(5);
});

test('transactions can be filtered by status', function () {
    Order::factory()->pending()->count(2)->create();
    Order::factory()->confirmed()->count(3)->create();
    Order::factory()->cancelled()->create();

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index', ['status' => 'confirmed']));

    $orders = $response->viewData('orders');
    expect($orders->total())->toBe(3);
});

test('transactions can be searched by order number', function () {
    $order = Order::factory()->create(['order_number' => 'MHR-20251221-ABC123']);
    Order::factory()->count(2)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index', ['search' => 'ABC123']));

    $orders = $response->viewData('orders');
    expect($orders->total())->toBe(1);
});

test('transactions can be searched by customer name', function () {
    Order::factory()->create(['customer_name' => 'Jane Doe']);
    Order::factory()->count(2)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index', ['search' => 'Jane']));

    $orders = $response->viewData('orders');
    expect($orders->total())->toBe(1);
});

test('transactions can be filtered by date range', function () {
    Order::factory()->create(['created_at' => now()->subDays(5)]);
    Order::factory()->create(['created_at' => now()->subDays(2)]);
    Order::factory()->create(['created_at' => now()]);

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index', [
        'date_from' => now()->subDays(3)->format('Y-m-d'),
        'date_to' => now()->format('Y-m-d'),
    ]));

    $orders = $response->viewData('orders');
    expect($orders->total())->toBe(2);
});

test('admin can view order detail', function () {
    $order = Order::factory()->create();
    OrderItem::factory()->count(2)->forOrder($order)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.show', $order));

    $response->assertStatus(200)
        ->assertViewIs('admin.transactions.show')
        ->assertViewHas('order')
        ->assertViewHas('statuses');
});

test('admin can update order status', function () {
    $order = Order::factory()->pending()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-status', $order), [
        'status' => Order::STATUS_CONFIRMED,
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    expect($order->fresh()->status)->toBe(Order::STATUS_CONFIRMED);
});

test('admin can update order status with notes', function () {
    $order = Order::factory()->pending()->create();

    $this->actingAs($this->admin)->put(route('admin.transactions.update-status', $order), [
        'status' => Order::STATUS_CONFIRMED,
        'notes' => 'Confirmed by admin',
    ]);

    expect($order->fresh()->status)->toBe(Order::STATUS_CONFIRMED)
        ->and($order->fresh()->admin_notes)->toBe('Confirmed by admin');
});

test('order status update validates status value', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-status', $order), [
        'status' => 'invalid_status',
    ]);

    $response->assertSessionHasErrors('status');
});

test('admin can update shipping information', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-shipping', $order), [
        'courier' => 'JNE',
        'tracking_number' => 'JNE123456789',
        'shipping_cost' => 25000,
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $order->refresh();
    expect($order->courier)->toBe('JNE')
        ->and($order->tracking_number)->toBe('JNE123456789')
        ->and($order->shipping_cost)->toEqual(25000.0);
});

test('updating shipping auto updates status to shipped', function () {
    $order = Order::factory()->confirmed()->create();

    $this->actingAs($this->admin)->put(route('admin.transactions.update-shipping', $order), [
        'courier' => 'JNE',
        'tracking_number' => 'JNE123456789',
    ]);

    expect($order->fresh()->status)->toBe(Order::STATUS_SHIPPED);
});

test('admin can update customer information', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-customer', $order), [
        'customer_name' => 'Updated Name',
        'customer_whatsapp' => '628987654321',
        'customer_email' => 'updated@example.com',
        'shipping_address' => 'New Address',
        'shipping_city' => 'New City',
        'shipping_province' => 'New Province',
        'shipping_postal_code' => '12345',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $order->refresh();
    expect($order->customer_name)->toBe('Updated Name')
        ->and($order->customer_whatsapp)->toBe('628987654321')
        ->and($order->customer_email)->toBe('updated@example.com');
});

test('customer update validates required fields', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-customer', $order), [
        'customer_name' => '',
    ]);

    $response->assertSessionHasErrors(['customer_name', 'customer_whatsapp', 'shipping_address']);
});

test('admin can update order notes', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-notes', $order), [
        'admin_notes' => 'Important notes about this order',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    expect($order->fresh()->admin_notes)->toBe('Important notes about this order');
});

test('admin can cancel order', function () {
    $order = Order::factory()->pending()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.cancel', $order), [
        'reason' => 'Customer requested cancellation',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $order->refresh();
    expect($order->status)->toBe(Order::STATUS_CANCELLED)
        ->and($order->admin_notes)->toBe('Customer requested cancellation');
});

test('admin can cancel order without reason', function () {
    $order = Order::factory()->pending()->create();

    $this->actingAs($this->admin)->put(route('admin.transactions.cancel', $order), []);

    expect($order->fresh()->status)->toBe(Order::STATUS_CANCELLED);
});

test('admin can delete order', function () {
    $order = Order::factory()->create();
    $orderId = $order->id;

    $response = $this->actingAs($this->admin)->delete(route('admin.transactions.destroy', $order));

    $response->assertRedirect(route('admin.transactions.index'))
        ->assertSessionHas('success');

    expect(Order::find($orderId))->toBeNull()
        ->and(Order::withTrashed()->find($orderId))->not->toBeNull();
});



test('transaction statistics are displayed', function () {
    Order::factory()->pending()->count(2)->create();
    Order::factory()->confirmed()->count(3)->create();
    Order::factory()->cancelled()->create();

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index'));

    $statistics = $response->viewData('statistics');

    expect($statistics)->toBeArray()
        ->and($statistics)->toHaveKey('total_orders')
        ->and($statistics)->toHaveKey('pending_orders')
        ->and($statistics['total_orders'])->toBe(6);
});

test('orders are displayed in recent order first', function () {
    $oldOrder = Order::factory()->create(['created_at' => now()->subDays(5)]);
    $newOrder = Order::factory()->create(['created_at' => now()]);

    $response = $this->actingAs($this->admin)->get(route('admin.transactions.index'));

    $orders = $response->viewData('orders');
    expect($orders->first()->id)->toBe($newOrder->id);
});

test('shipping update validates required fields', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-shipping', $order), [
        'courier' => '',
    ]);

    $response->assertSessionHasErrors(['courier', 'tracking_number']);
});

test('notes update validates required field', function () {
    $order = Order::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.transactions.update-notes', $order), [
        'admin_notes' => '',
    ]);

    $response->assertSessionHasErrors('admin_notes');
});
