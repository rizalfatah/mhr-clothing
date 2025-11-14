<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('admin dashboard shows total products count', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(10)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_products'] === 10;
        });
});

test('admin dashboard shows active products count', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(7)->create(['is_active' => true]);
    Product::factory()->count(3)->create(['is_active' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['active_products'] === 7;
        });
});

test('admin dashboard shows total categories count', function () {
    $admin = User::factory()->admin()->create();
    Category::factory()->count(5)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_categories'] === 5;
        });
});

test('admin dashboard shows featured products count', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(3)->featured()->create();
    Product::factory()->count(7)->create(['is_featured' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['featured_products'] === 3;
        });
});

test('admin dashboard shows recent products', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(10)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('recent_products', function ($products) {
            return $products->count() === 5;
        });
});

test('recent products are ordered by latest first', function () {
    $admin = User::factory()->admin()->create();
    $product1 = Product::factory()->create(['created_at' => now()->subDays(5)]);
    $product2 = Product::factory()->create(['created_at' => now()->subDays(1)]);
    $product3 = Product::factory()->create(['created_at' => now()->subDays(3)]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('recent_products', function ($products) use ($product2, $product3, $product1) {
            return $products[0]->id === $product2->id &&
                   $products[1]->id === $product3->id &&
                   $products[2]->id === $product1->id;
        });
});

test('recent products are limited to 5', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(20)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('recent_products', function ($products) {
            return $products->count() === 5;
        });
});

test('dashboard statistics are accurate with mixed data', function () {
    $admin = User::factory()->admin()->create();

    // Create products with different states
    Product::factory()->count(5)->create(['is_active' => true, 'is_featured' => true]);
    Product::factory()->count(3)->create(['is_active' => true, 'is_featured' => false]);
    Product::factory()->count(2)->create(['is_active' => false, 'is_featured' => false]);

    // Create categories
    Category::factory()->count(4)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_products'] === 10 &&
                   $stats['active_products'] === 8 &&
                   $stats['total_categories'] === 4 &&
                   $stats['featured_products'] === 5;
        });
});

test('dashboard shows zero counts when no data exists', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_products'] === 0 &&
                   $stats['active_products'] === 0 &&
                   $stats['total_categories'] === 0 &&
                   $stats['featured_products'] === 0;
        });
});

test('recent products include category relationship', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create(['name' => 'Test Category']);
    Product::factory()->forCategory($category)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('recent_products', function ($products) {
            return $products->first()->category !== null &&
                   $products->first()->category->name === 'Test Category';
        });
});

test('dashboard includes both active and inactive products in total', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(6)->create(['is_active' => true]);
    Product::factory()->count(4)->create(['is_active' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_products'] === 10 &&
                   $stats['active_products'] === 6;
        });
});

test('dashboard includes both featured and non-featured products in total', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(3)->create(['is_featured' => true]);
    Product::factory()->count(7)->create(['is_featured' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_products'] === 10 &&
                   $stats['featured_products'] === 3;
        });
});

test('dashboard counts soft deleted products', function () {
    $admin = User::factory()->admin()->create();
    Product::factory()->count(5)->create();
    $productToDelete = Product::factory()->create();
    $productToDelete->delete();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewHas('stats', function ($stats) {
            // Soft deleted products should not be counted
            return $stats['total_products'] === 5;
        });
});

test('non-admin cannot access dashboard', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertForbidden();
});
