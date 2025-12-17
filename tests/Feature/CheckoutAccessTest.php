<?php

use App\Models\Product;
use App\Models\User;

describe('Checkout Access Control', function () {
    beforeEach(function () {
        // Create a product for testing
        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'selling_price' => 100000,
        ]);
    });

    describe('Checkout Page Access', function () {
        test('guest user can access checkout page with items in cart', function () {
            // Add item to cart as guest
            $this->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->get(route('checkout.index'));

            $response->assertOk();
            $response->assertViewIs('checkout');
        });

        test('guest user redirected to catalog when cart is empty', function () {
            $response = $this->get(route('checkout.index'));

            $response->assertRedirect(route('catalog'));
            $response->assertSessionHas('error');
        });

        test('verified user can access checkout page', function () {
            $user = User::factory()->create([
                'email_verified_at' => now(),
            ]);

            // Add item to cart
            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->actingAs($user)->get(route('checkout.index'));

            $response->assertOk();
            $response->assertViewIs('checkout');
        });

        test('unverified user cannot access checkout page', function () {
            $user = User::factory()->unverified()->create();

            // Add item to cart
            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->actingAs($user)->get(route('checkout.index'));

            $response->assertRedirect(route('verification.notice'));
            $response->assertSessionHas('error', 'Anda harus memverifikasi email terlebih dahulu sebelum melakukan checkout.');
        });
    });

    describe('Checkout Process', function () {
        test('guest user can process checkout', function () {
            // Add item to cart as guest
            $this->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

            $response = $this->post(route('checkout.process'), [
                'customer_name' => 'John Doe',
                'customer_whatsapp' => '081234567890',
                'customer_email' => 'john@example.com',
                'shipping_address' => 'Jl. Test No. 123',
                'shipping_city' => 'Jakarta',
                'shipping_province' => 'DKI Jakarta',
                'shipping_postal_code' => '12345',
                'shipping_notes' => 'Test notes',
            ]);

            $response->assertRedirect();
            expect($response->headers->get('Location'))->toContain('checkout/success');

            // Cart should be cleared
            $cartResponse = $this->get(route('cart.get'));
            $cartData = $cartResponse->json();
            expect($cartData['count'])->toBe(0);
        });

        test('verified user can process checkout', function () {
            $user = User::factory()->create([
                'email_verified_at' => now(),
            ]);

            // Add item to cart
            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

            $response = $this->actingAs($user)->post(route('checkout.process'), [
                'customer_name' => 'Jane Doe',
                'customer_whatsapp' => '081234567891',
                'customer_email' => 'jane@example.com',
                'shipping_address' => 'Jl. Test No. 456',
                'shipping_city' => 'Bandung',
                'shipping_province' => 'Jawa Barat',
                'shipping_postal_code' => '40123',
            ]);

            $response->assertRedirect();
            expect($response->headers->get('Location'))->toContain('checkout/success');
        });

        test('unverified user cannot process checkout', function () {
            $user = User::factory()->unverified()->create();

            // Add item to cart
            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->actingAs($user)->post(route('checkout.process'), [
                'customer_name' => 'Unverified User',
                'customer_whatsapp' => '081234567892',
                'shipping_address' => 'Jl. Test No. 789',
                'shipping_city' => 'Surabaya',
                'shipping_province' => 'Jawa Timur',
            ]);

            $response->assertRedirect(route('verification.notice'));
            $response->assertSessionHas('error');
        });

        test('checkout process validates required fields', function () {
            // Add item to cart
            $this->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->post(route('checkout.process'), [
                // Missing required fields
            ]);

            $response->assertSessionHasErrors([
                'customer_name',
                'customer_whatsapp',
                'shipping_address',
                'shipping_city',
                'shipping_province',
            ]);
        });
    });

    describe('Checkout Success Page', function () {
        test('guest user can access success page with valid order', function () {
            // Create order as guest
            $this->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $checkoutResponse = $this->post(route('checkout.process'), [
                'customer_name' => 'Test Guest',
                'customer_whatsapp' => '081234567890',
                'shipping_address' => 'Test Address',
                'shipping_city' => 'Test City',
                'shipping_province' => 'Test Province',
            ]);

            // Follow the redirect to success page
            $checkoutResponse->assertRedirect();
            $response = $this->followingRedirects()->get($checkoutResponse->headers->get('Location'));
            $response->assertOk();
        });

        test('verified user can access success page', function () {
            $user = User::factory()->create([
                'email_verified_at' => now(),
            ]);

            // Create order
            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $checkoutResponse = $this->actingAs($user)->post(route('checkout.process'), [
                'customer_name' => 'Test User',
                'customer_whatsapp' => '081234567890',
                'shipping_address' => 'Test Address',
                'shipping_city' => 'Test City',
                'shipping_province' => 'Test Province',
            ]);

            // Follow the redirect to success page
            $checkoutResponse->assertRedirect();
            $response = $this->actingAs($user)->followingRedirects()->get($checkoutResponse->headers->get('Location'));
            $response->assertOk();
        });

        test('unverified user can access success page with valid order', function () {
            // Unverified users can access success page if they have a valid order
            // This is intentional - they already completed checkout as guest or when verified
            $user = User::factory()->unverified()->create();

            // Create order as guest first
            $this->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $checkoutResponse = $this->post(route('checkout.process'), [
                'customer_name' => 'Test',
                'customer_whatsapp' => '081234567890',
                'shipping_address' => 'Address',
                'shipping_city' => 'City',
                'shipping_province' => 'Province',
            ]);

            // Follow the redirect to success page, then try to access as unverified user
            $checkoutResponse->assertRedirect();
            $response = $this->actingAs($user)->followingRedirects()->get($checkoutResponse->headers->get('Location'));
            $response->assertOk();
        });
    });

    describe('Middleware Behavior', function () {
        test('middleware allows guest on checkout index', function () {
            $this->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->get(route('checkout.index'));
            $response->assertOk();
        });

        test('middleware blocks unverified user on checkout index', function () {
            $user = User::factory()->unverified()->create();

            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->actingAs($user)->get(route('checkout.index'));
            $response->assertRedirect(route('verification.notice'));
        });

        test('middleware allows verified user on checkout index', function () {
            $user = User::factory()->create(['email_verified_at' => now()]);

            $this->actingAs($user)->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

            $response = $this->actingAs($user)->get(route('checkout.index'));
            $response->assertOk();
        });
    });
});
