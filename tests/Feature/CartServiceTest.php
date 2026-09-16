<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;

test('deleted products are removed from an authenticated users cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
    ]);

    CartItem::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'quantity' => 2,
    ]);

    $product->delete();
    $this->actingAs($user);

    $cartService = app(CartService::class);

    expect($cartService->getItems())->toBe([])
        ->and($cartService->getCount())->toBe(0)
        ->and(CartItem::where('user_id', $user->id)->exists())->toBeFalse();
});
