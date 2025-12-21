<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

test('product can be created with required attributes', function () {
    $product = Product::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Test Product',
        'selling_price' => 100000,
    ]);

    expect($product->name)->toBe('Test Product')
        ->and($product->selling_price)->toEqual(100000.00)
        ->and($product->category_id)->toBe($this->category->id);
});

test('product has category relationship', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);

    expect($product->category)->toBeInstanceOf(Category::class)
        ->and($product->category->id)->toBe($this->category->id);
});

test('product has variants relationship', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->count(3)->create([
        'product_id' => $product->id,
    ]);

    expect($product->variants)->toHaveCount(3)
        ->and($product->variants->first())->toBeInstanceOf(ProductVariant::class);
});

test('product price attribute returns selling price', function () {
    $product = Product::factory()->create(['selling_price' => 150000]);

    expect($product->price)->toEqual(150000.00);
});

test('product can check if it has available stock', function () {
    $product = Product::factory()->create();

    // Create variant with stock
    ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
        'is_available' => true,
    ]);

    expect($product->hasAvailableStock())->toBeTrue();
});

test('product has no available stock when variants are out of stock', function () {
    $product = Product::factory()->create();

    // Create variant without stock
    ProductVariant::factory()->outOfStock()->create([
        'product_id' => $product->id,
    ]);

    expect($product->hasAvailableStock())->toBeFalse();
});

test('product has no available stock when variants are unavailable', function () {
    $product = Product::factory()->create();

    // Create variant with stock but unavailable
    ProductVariant::factory()->unavailable()->create([
        'product_id' => $product->id,
        'stock' => 10,
    ]);

    expect($product->hasAvailableStock())->toBeFalse();
});

test('product total stock is sum of all variant stocks', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
    ]);

    ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 15,
    ]);

    ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 5,
    ]);

    expect($product->total_stock)->toBe(30);
});

test('product available variants only returns variants with stock', function () {
    $product = Product::factory()->create();

    // Available variants
    ProductVariant::factory()->inStock()->create(['product_id' => $product->id]);
    ProductVariant::factory()->inStock()->create(['product_id' => $product->id]);

    // Out of stock variant
    ProductVariant::factory()->outOfStock()->create(['product_id' => $product->id]);

    expect($product->availableVariants)->toHaveCount(2);
});

test('product can be soft deleted', function () {
    $product = Product::factory()->create();
    $productId = $product->id;

    $product->delete();

    expect(Product::find($productId))->toBeNull()
        ->and(Product::withTrashed()->find($productId))->not->toBeNull();
});

test('product is active by default', function () {
    $product = Product::factory()->create();

    expect($product->is_active)->toBeTrue();
});

test('product can be inactive', function () {
    $product = Product::factory()->inactive()->create();

    expect($product->is_active)->toBeFalse();
});

test('product can be featured', function () {
    $product = Product::factory()->featured()->create();

    expect($product->is_featured)->toBeTrue();
});

test('product attributes are cast correctly', function () {
    $product = Product::factory()->create([
        'selling_price' => 100000.50,
        'original_price' => 150000.75,
        'rating' => 4.5,
        'is_active' => true,
        'is_featured' => false,
    ]);

    expect((float) $product->selling_price)->toBeFloat()
        ->and((float) $product->original_price)->toBeFloat()
        ->and((float) $product->rating)->toBeFloat()
        ->and($product->is_active)->toBeBool()
        ->and($product->is_featured)->toBeBool();
});
