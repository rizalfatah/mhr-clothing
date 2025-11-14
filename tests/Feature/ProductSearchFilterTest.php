<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

test('catalog only shows active products', function () {
    Product::factory()->count(3)->create(['is_active' => true]);
    Product::factory()->count(2)->create(['is_active' => false]);

    $response = $this->get(route('catalog'));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 3;
        });
});

test('products can be filtered by category', function () {
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();

    Product::factory()->count(3)->forCategory($category1)->create(['is_active' => true]);
    Product::factory()->count(2)->forCategory($category2)->create(['is_active' => true]);

    $response = $this->get(route('catalog', ['category' => $category1->id]));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 3;
        });
});

test('products can be searched by name', function () {
    Product::factory()->create(['name' => 'Red Shirt', 'is_active' => true]);
    Product::factory()->create(['name' => 'Blue Pants', 'is_active' => true]);
    Product::factory()->create(['name' => 'Red Hat', 'is_active' => true]);

    $response = $this->get(route('catalog', ['search' => 'Red']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 2;
        });
});

test('products are sorted by latest by default', function () {
    $product1 = Product::factory()->create(['created_at' => now()->subDays(3), 'is_active' => true]);
    $product2 = Product::factory()->create(['created_at' => now()->subDays(1), 'is_active' => true]);
    $product3 = Product::factory()->create(['created_at' => now()->subDays(2), 'is_active' => true]);

    $response = $this->get(route('catalog'));

    $response->assertOk()
        ->assertViewHas('products', function ($products) use ($product2, $product3, $product1) {
            return $products->items()[0]->id === $product2->id &&
                   $products->items()[1]->id === $product3->id &&
                   $products->items()[2]->id === $product1->id;
        });
});

test('products can be sorted by price low to high', function () {
    Product::factory()->create(['selling_price' => 150000, 'is_active' => true]);
    Product::factory()->create(['selling_price' => 50000, 'is_active' => true]);
    Product::factory()->create(['selling_price' => 100000, 'is_active' => true]);

    $response = $this->get(route('catalog', ['sort' => 'price_low']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return (float)$products->items()[0]->selling_price === 50000.0 &&
                   (float)$products->items()[1]->selling_price === 100000.0 &&
                   (float)$products->items()[2]->selling_price === 150000.0;
        });
});

test('products can be sorted by price high to low', function () {
    Product::factory()->create(['selling_price' => 50000, 'is_active' => true]);
    Product::factory()->create(['selling_price' => 150000, 'is_active' => true]);
    Product::factory()->create(['selling_price' => 100000, 'is_active' => true]);

    $response = $this->get(route('catalog', ['sort' => 'price_high']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return (float)$products->items()[0]->selling_price === 150000.0 &&
                   (float)$products->items()[1]->selling_price === 100000.0 &&
                   (float)$products->items()[2]->selling_price === 50000.0;
        });
});

test('products can be sorted by name', function () {
    Product::factory()->create(['name' => 'Zebra Product', 'is_active' => true]);
    Product::factory()->create(['name' => 'Apple Product', 'is_active' => true]);
    Product::factory()->create(['name' => 'Mango Product', 'is_active' => true]);

    $response = $this->get(route('catalog', ['sort' => 'name']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->items()[0]->name === 'Apple Product' &&
                   $products->items()[1]->name === 'Mango Product' &&
                   $products->items()[2]->name === 'Zebra Product';
        });
});

test('catalog shows only active categories', function () {
    Category::factory()->count(3)->create(['is_active' => true]);
    Category::factory()->count(2)->create(['is_active' => false]);

    $response = $this->get(route('catalog'));

    $response->assertOk()
        ->assertViewHas('categories', function ($categories) {
            return $categories->count() === 3;
        });
});

test('category filter and search work together', function () {
    $category = Category::factory()->create();

    Product::factory()->create(['name' => 'Red Shirt', 'category_id' => $category->id, 'is_active' => true]);
    Product::factory()->create(['name' => 'Blue Shirt', 'category_id' => $category->id, 'is_active' => true]);
    Product::factory()->create(['name' => 'Red Pants', 'is_active' => true]);

    $response = $this->get(route('catalog', [
        'category' => $category->id,
        'search' => 'Shirt'
    ]));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 2;
        });
});

test('search and sort work together', function () {
    Product::factory()->create(['name' => 'Red Product A', 'selling_price' => 150000, 'is_active' => true]);
    Product::factory()->create(['name' => 'Red Product B', 'selling_price' => 50000, 'is_active' => true]);
    Product::factory()->create(['name' => 'Blue Product', 'selling_price' => 100000, 'is_active' => true]);

    $response = $this->get(route('catalog', [
        'search' => 'Red',
        'sort' => 'price_low'
    ]));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 2 &&
                   (float)$products->items()[0]->selling_price === 50000.0 &&
                   (float)$products->items()[1]->selling_price === 150000.0;
        });
});

test('product detail page shows product by slug', function () {
    $product = Product::factory()->create(['slug' => 'test-product', 'is_active' => true]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('product', function ($viewProduct) use ($product) {
            return $viewProduct->id === $product->id;
        });
});

test('inactive product cannot be viewed', function () {
    $product = Product::factory()->create(['slug' => 'test-product', 'is_active' => false]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertNotFound();
});

test('related products are from same category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->forCategory($category)->create(['is_active' => true]);

    $relatedInCategory = Product::factory()->count(3)
        ->forCategory($category)
        ->create(['is_active' => true]);

    $otherCategory = Category::factory()->create();
    $productsInOtherCategory = Product::factory()->count(2)
        ->forCategory($otherCategory)
        ->create(['is_active' => true]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('relatedProducts', function ($related) use ($category) {
            return $related->every(function ($p) use ($category) {
                return $p->category_id === $category->id;
            });
        });
});

test('related products do not include current product', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->forCategory($category)->create(['is_active' => true]);

    Product::factory()->count(3)
        ->forCategory($category)
        ->create(['is_active' => true]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('relatedProducts', function ($related) use ($product) {
            return $related->every(function ($p) use ($product) {
                return $p->id !== $product->id;
            });
        });
});

test('related products are limited to 4', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->forCategory($category)->create(['is_active' => true]);

    Product::factory()->count(10)
        ->forCategory($category)
        ->create(['is_active' => true]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('relatedProducts', function ($related) {
            return $related->count() === 4;
        });
});

test('related products only include active products', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->forCategory($category)->create(['is_active' => true]);

    Product::factory()->count(2)
        ->forCategory($category)
        ->create(['is_active' => true]);

    Product::factory()->count(3)
        ->forCategory($category)
        ->create(['is_active' => false]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('relatedProducts', function ($related) {
            return $related->count() === 2 &&
                   $related->every(fn($p) => $p->is_active === true);
        });
});

test('products are paginated with 12 per page', function () {
    Product::factory()->count(25)->create(['is_active' => true]);

    $response = $this->get(route('catalog'));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 12 &&
                   $products->total() === 25;
        });
});

test('search is case insensitive', function () {
    Product::factory()->create(['name' => 'Red Shirt', 'is_active' => true]);
    Product::factory()->create(['name' => 'BLUE PANTS', 'is_active' => true]);

    $response = $this->get(route('catalog', ['search' => 'blue']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 1;
        });
});

test('search finds partial matches', function () {
    Product::factory()->create(['name' => 'Cotton Shirt', 'is_active' => true]);
    Product::factory()->create(['name' => 'Polyester Shirt', 'is_active' => true]);
    Product::factory()->create(['name' => 'Denim Pants', 'is_active' => true]);

    $response = $this->get(route('catalog', ['search' => 'Shirt']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) {
            return $products->count() === 2;
        });
});

test('product detail page loads category relationship', function () {
    $category = Category::factory()->create(['name' => 'T-Shirts']);
    $product = Product::factory()->forCategory($category)->create(['is_active' => true]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('product', function ($viewProduct) use ($category) {
            return $viewProduct->category->name === 'T-Shirts';
        });
});

test('product detail page loads all images', function () {
    $product = Product::factory()->create(['is_active' => true]);
    ProductImage::factory()->count(3)->forProduct($product)->create();

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk()
        ->assertViewHas('product', function ($viewProduct) {
            return $viewProduct->images->count() === 3;
        });
});
