<?php

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;

uses()->group('feature');

beforeEach(function () {
    // Additional setup can go here if needed
});

test('admin can create coupon', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.coupons.store'), [
        'code' => 'TEST10',
        'type' => 'fixed',
        'value' => 10000,
        'is_active' => true,
    ])->assertRedirect();

    $this->assertDatabaseHas('coupons', ['code' => 'TEST10']);
});

test('user can apply valid coupon', function () {
    $coupon = Coupon::create([
        'code' => 'SAVE10',
        'type' => 'fixed',
        'value' => 10000,
        'is_active' => true,
    ]);

    $response = $this->postJson(route('checkout.coupon.apply'), ['code' => 'SAVE10']);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    expect(Session::get('applied_coupon'))->toBe('SAVE10');
});

test('user cannot apply invalid coupon', function () {
    $response = $this->postJson(route('checkout.coupon.apply'), ['code' => 'INVALID']);

    $response->assertStatus(400)
        ->assertJson(['success' => false]);
});

test('coupon discount calculation fixed', function () {
    // 1. Setup Product and Add to Cart
    $product = Product::factory()->create(['selling_price' => 50000]);
    $variant = \App\Models\ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
        'price_adjustment' => 0
    ]);

    $this->postJson(route('cart.add'), [
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'quantity' => 1
    ]);

    // 2. Create Coupon
    Coupon::create([
        'code' => 'FIXED10',
        'type' => 'fixed',
        'value' => 10000,
        'is_active' => true,
    ]);

    // 3. Apply Coupon
    $this->postJson(route('checkout.coupon.apply'), ['code' => 'FIXED10']);

    // 4. Check Cart Summary
    $this->json('GET', route('cart.get'))
        ->assertJson([
            'discount' => 10000,
            'subtotal' => 50000,
        ]);
});

test('coupon discount calculation percent', function () {
    // 1. Setup Product and Add to Cart
    $product = Product::factory()->create(['selling_price' => 100000]);
    $variant = \App\Models\ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
        'price_adjustment' => 0
    ]);

    $this->postJson(route('cart.add'), [
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'quantity' => 1
    ]);

    // 2. Create Coupon
    Coupon::create([
        'code' => 'PERC50',
        'type' => 'percent',
        'value' => 50,
        'is_active' => true,
    ]);

    // 3. Apply Coupon
    $this->postJson(route('checkout.coupon.apply'), ['code' => 'PERC50']);

    // 4. Check Cart Summary
    $this->json('GET', route('cart.get'))
        ->assertJson([
            'discount' => 50000,
            'subtotal' => 100000,
        ]);
});

test('usage limit is respected', function () {
    $coupon = Coupon::create([
        'code' => 'LIMIT1',
        'type' => 'fixed',
        'value' => 1000,
        'usage_limit' => 1,
        'is_active' => true,
    ]);

    // Create a dummy order for usage record
    $order = \App\Models\Order::create([
        'order_number' => 'ORD-TEST',
        'total' => 10000,
        'subtotal' => 10000,
        'shipping_cost' => 0,
        'discount' => 0,
        'customer_name' => 'Test',
        'customer_whatsapp' => '08123',
        'shipping_address' => 'Test',
        'shipping_city' => 'Test',
        'shipping_province' => 'Test',
        'shipping_postal_code' => '12345',
        'status' => 'pending'
    ]);

    // Create a usage manually
    CouponUsage::create([
        'coupon_id' => $coupon->id,
        'order_id' => $order->id,
        'discount_amount' => 1000,
        'created_at' => now(),
    ]);

    $response = $this->postJson(route('checkout.coupon.apply'), ['code' => 'LIMIT1']);
    $response->assertStatus(400)->assertJson(['message' => 'Kupon tidak valid atau sudah kadaluarsa.']);
});

test('checkout process creates usage record', function () {
    $admin = User::factory()->admin()->create();

    // Setup Cart
    $product = Product::factory()->create(['selling_price' => 100000]);
    $variant = \App\Models\ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
        'price_adjustment' => 0
    ]);

    $this->postJson(route('cart.add'), [
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'quantity' => 1
    ]);

    // Create Coupon
    $coupon = Coupon::create([
        'code' => 'REALDEAL',
        'type' => 'fixed',
        'value' => 20000,
        'is_active' => true,
    ]);

    // Apply Coupon
    $this->postJson(route('checkout.coupon.apply'), ['code' => 'REALDEAL']);

    $data = [
        'customer_name' => 'John Doe',
        'customer_whatsapp' => '08123456789',
        'shipping_address' => 'Jalan Jalan',
        'shipping_city' => 'Jakarta',
        'shipping_province' => 'DKI Jakarta',
        'shipping_postal_code' => '12345',
    ];

    $response = $this->post(route('checkout.process'), $data);

    if (session('error')) {
        dump(session('error'));
    }
    if (session('errors')) {
        dump(session('errors')->all());
    }

    $response->assertRedirect();

    $this->assertDatabaseHas('coupon_usages', [
        'coupon_id' => $coupon->id,
        'discount_amount' => 20000
    ]);

    $this->assertDatabaseHas('orders', [
        'discount' => 20000
    ]);
});
