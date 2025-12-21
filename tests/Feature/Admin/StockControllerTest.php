<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'email' => 'admin@test.com',
        'role' => 'admin',
    ]);

    $this->customer = User::factory()->create([
        'email' => 'customer@test.com',
        'role' => 'customer',
    ]);

    $this->category = Category::factory()->create(['name' => 'T-Shirts']);
});

test('admin can access stock listing page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.stock.index'));

    $response->assertStatus(200)
        ->assertViewIs('admin.stock.index')
        ->assertViewHas('stocks')
        ->assertViewHas('categories');
});

test('non-admin cannot access stock listing page', function () {
    $response = $this->actingAs($this->customer)->get(route('admin.stock.index'));

    $response->assertStatus(403);
});

test('guest cannot access stock listing page', function () {
    $response = $this->get(route('admin.stock.index'));

    $response->assertRedirect(route('login'));
});

test('stock listing shows product variants', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);

    ProductVariant::factory()->count(3)->create(['product_id' => $product->id]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index'));

    $response->assertStatus(200);

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(3);
});

test('stock can be filtered by category', function () {
    $category1 = Category::factory()->create(['name' => 'Category 1']);
    $category2 = Category::factory()->create(['name' => 'Category 2']);

    $product1 = Product::factory()->create(['category_id' => $category1->id]);
    $product2 = Product::factory()->create(['category_id' => $category2->id]);

    ProductVariant::factory()->count(2)->create(['product_id' => $product1->id]);
    ProductVariant::factory()->count(3)->create(['product_id' => $product2->id]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['category_id' => $category1->id]));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(2);
});

test('stock can be filtered by out of stock status', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->outOfStock()->count(2)->create(['product_id' => $product->id]);
    ProductVariant::factory()->inStock()->count(3)->create(['product_id' => $product->id]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['status' => 'out_of_stock']));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(2);
});

test('stock can be filtered by low stock status', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->lowStock()->count(2)->create(['product_id' => $product->id]);
    ProductVariant::factory()->inStock()->count(3)->create(['product_id' => $product->id]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['status' => 'low_stock']));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(2);
});

test('stock can be filtered by in stock status', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->inStock()->count(3)->create(['product_id' => $product->id]);
    ProductVariant::factory()->lowStock()->count(2)->create(['product_id' => $product->id]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['status' => 'in_stock']));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(3);
});

test('stock can be searched by product name', function () {
    $product1 = Product::factory()->create(['name' => 'Blue Shirt']);
    $product2 = Product::factory()->create(['name' => 'Red Pants']);

    ProductVariant::factory()->create(['product_id' => $product1->id]);
    ProductVariant::factory()->create(['product_id' => $product2->id]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['search' => 'Blue']));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(1);
});

test('stock can be searched by sku', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->create(['product_id' => $product->id, 'sku' => 'SKU-ABC123']);
    ProductVariant::factory()->create(['product_id' => $product->id, 'sku' => 'SKU-XYZ789']);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['search' => 'ABC123']));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(1);
});

test('stock can be sorted by stock ascending', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 50]);
    ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 10]);
    ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 30]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['sort' => 'stock_asc']));

    $stocks = $response->viewData('stocks');
    expect($stocks->first()->stock)->toBe(10);
});

test('stock can be sorted by stock descending', function () {
    $product = Product::factory()->create();

    ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 10]);
    ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 50]);
    ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 30]);

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index', ['sort' => 'stock_desc']));

    $stocks = $response->viewData('stocks');
    expect($stocks->first()->stock)->toBe(50);
});

test('admin can update stock', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.stock.update', $variant->id), [
        'stock' => 25,
    ]);

    $response->assertRedirect();

    expect($variant->fresh()->stock)->toBe(25);
});

test('updating stock to zero auto disables variant', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
        'is_available' => true,
    ]);

    $this->actingAs($this->admin)->put(route('admin.stock.update', $variant->id), [
        'stock' => 0,
    ]);

    expect($variant->fresh()->stock)->toBe(0)
        ->and($variant->fresh()->is_available)->toBeFalse();
});

test('updating stock from zero auto enables variant', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->outOfStock()->create([
        'product_id' => $product->id,
    ]);

    $this->actingAs($this->admin)->put(route('admin.stock.update', $variant->id), [
        'stock' => 15,
    ]);

    expect($variant->fresh()->stock)->toBe(15)
        ->and($variant->fresh()->is_available)->toBeTrue();
});

test('stock update returns json response for ajax requests', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 10,
    ]);

    $response = $this->actingAs($this->admin)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('admin.stock.update', $variant->id), [
            'stock' => 20,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'new_stock' => 20,
            'variant_id' => $variant->id,
        ]);
});

test('stock update validates minimum stock value', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $response = $this->actingAs($this->admin)->put(route('admin.stock.update', $variant->id), [
        'stock' => -5,
    ]);

    $response->assertSessionHasErrors('stock');
});

test('stock update requires stock field', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $response = $this->actingAs($this->admin)->put(route('admin.stock.update', $variant->id), []);

    $response->assertSessionHasErrors('stock');
});

test('stock listing excludes soft deleted products', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $product->delete();

    $response = $this->actingAs($this->admin)->get(route('admin.stock.index'));

    $stocks = $response->viewData('stocks');
    expect($stocks->total())->toBe(0);
});
