<?php

use App\Models\Product;
use App\Models\ProductVariant;

beforeEach(function () {
    $this->product = Product::factory()->create(['selling_price' => 100000]);
});

test('product variant can be created with required attributes', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'size' => ProductVariant::SIZE_MEDIUM,
        'stock' => 10,
    ]);

    expect($variant->size)->toBe(ProductVariant::SIZE_MEDIUM)
        ->and($variant->stock)->toBe(10)
        ->and($variant->product_id)->toBe($this->product->id);
});

test('product variant belongs to product', function () {
    $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

    expect($variant->product)->toBeInstanceOf(Product::class)
        ->and($variant->product->id)->toBe($this->product->id);
});

test('product variant has all available sizes', function () {
    $sizes = ProductVariant::getAvailableSizes();

    expect($sizes)->toBeArray()
        ->and($sizes)->toContain('Small', 'Medium', 'Large', 'XL', 'XXL')
        ->and($sizes)->toHaveCount(5);
});

test('product variant has stock check', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'stock' => 10,
        'is_available' => true,
    ]);

    expect($variant->hasStock())->toBeTrue()
        ->and($variant->hasStock(5))->toBeTrue()
        ->and($variant->hasStock(10))->toBeTrue()
        ->and($variant->hasStock(11))->toBeFalse();
});

test('product variant has no stock when unavailable', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'stock' => 10,
        'is_available' => false,
    ]);

    expect($variant->hasStock())->toBeFalse();
});

test('product variant has no stock when stock is zero', function () {
    $variant = ProductVariant::factory()->outOfStock()->create([
        'product_id' => $this->product->id,
    ]);

    expect($variant->hasStock())->toBeFalse();
});

test('product variant can decrement stock', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'stock' => 10,
        'is_available' => true,
    ]);

    $result = $variant->decrementStock(3);

    expect($result)->toBeTrue()
        ->and($variant->fresh()->stock)->toBe(7)
        ->and($variant->fresh()->is_available)->toBeTrue();
});

test('product variant auto disables when stock reaches zero', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'stock' => 3,
        'is_available' => true,
    ]);

    $variant->decrementStock(3);

    expect($variant->fresh()->stock)->toBe(0)
        ->and($variant->fresh()->is_available)->toBeFalse();
});

test('product variant cannot decrement stock below zero', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'stock' => 5,
        'is_available' => true,
    ]);

    $result = $variant->decrementStock(10);

    expect($result)->toBeFalse()
        ->and($variant->fresh()->stock)->toBe(5);
});

test('product variant can increment stock', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'stock' => 5,
        'is_available' => true,
    ]);

    $variant->incrementStock(10);

    expect($variant->fresh()->stock)->toBe(15)
        ->and($variant->fresh()->is_available)->toBeTrue();
});

test('product variant auto enables when stock is added', function () {
    $variant = ProductVariant::factory()->outOfStock()->create([
        'product_id' => $this->product->id,
    ]);

    $variant->incrementStock(5);

    expect($variant->fresh()->stock)->toBe(5)
        ->and($variant->fresh()->is_available)->toBeTrue();
});

test('product variant calculates final price with adjustment', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'price_adjustment' => 10000,
    ]);

    expect($variant->final_price)->toBe(110000.0); // 100000 + 10000
});

test('product variant calculates final price without adjustment', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'price_adjustment' => 0,
    ]);

    expect($variant->final_price)->toBe(100000.0);
});

test('product variant available scope filters correctly', function () {
    // Available variants
    ProductVariant::factory()->inStock()->create(['product_id' => $this->product->id]);
    ProductVariant::factory()->inStock()->create(['product_id' => $this->product->id]);

    // Unavailable variants
    ProductVariant::factory()->outOfStock()->create(['product_id' => $this->product->id]);
    ProductVariant::factory()->unavailable()->create([
        'product_id' => $this->product->id,
        'stock' => 10,
    ]);

    $availableVariants = ProductVariant::available()->get();

    expect($availableVariants)->toHaveCount(2);
});

test('product variant attributes are cast correctly', function () {
    $variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'price_adjustment' => 5000.50,
        'stock' => 10,
        'is_available' => true,
    ]);

    expect((float) $variant->price_adjustment)->toBeFloat()
        ->and($variant->stock)->toBeInt()
        ->and($variant->is_available)->toBeBool();
});

test('product variant can be created for each size', function () {
    $sizes = ProductVariant::getAvailableSizes();

    foreach ($sizes as $size) {
        $variant = ProductVariant::factory()->size($size)->create([
            'product_id' => $this->product->id,
        ]);

        expect($variant->size)->toBe($size);
    }

    expect($this->product->variants)->toHaveCount(5);
});
