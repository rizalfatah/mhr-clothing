<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->service = new ProductService();
    Storage::fake('public');
});

test('product can be created with valid data', function () {
    $category = Category::factory()->create();

    $data = [
        'category_id' => $category->id,
        'name' => 'Test Product',
        'description' => 'Test Description',
        'details' => 'Test Details',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
        'is_active' => true,
        'is_featured' => false,
    ];

    $product = $this->service->createProduct($data);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->name)->toBe('Test Product')
        ->and($product->slug)->toBe('test-product')
        ->and($product->category_id)->toBe($category->id);
});

test('product slug is generated from name', function () {
    $category = Category::factory()->create();

    $data = [
        'category_id' => $category->id,
        'name' => 'Cool Product Name',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
    ];

    $product = $this->service->createProduct($data);

    expect($product->slug)->toBe('cool-product-name');
});

test('product can be created with images', function () {
    $category = Category::factory()->create();
    $images = [
        UploadedFile::fake()->image('product1.jpg'),
        UploadedFile::fake()->image('product2.jpg'),
    ];

    $data = [
        'category_id' => $category->id,
        'name' => 'Product with Images',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
    ];

    $product = $this->service->createProduct($data, $images);

    expect($product->images)->toHaveCount(2)
        ->and(Storage::disk('public')->exists($product->images[0]->image_path))->toBeTrue()
        ->and(Storage::disk('public')->exists($product->images[1]->image_path))->toBeTrue();
});

test('first image is set as primary when creating product', function () {
    $category = Category::factory()->create();
    $images = [
        UploadedFile::fake()->image('product1.jpg'),
        UploadedFile::fake()->image('product2.jpg'),
    ];

    $data = [
        'category_id' => $category->id,
        'name' => 'Product with Images',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
    ];

    $product = $this->service->createProduct($data, $images);

    $primaryImage = $product->images->where('is_primary', true)->first();

    expect($primaryImage)->not->toBeNull()
        ->and($primaryImage->sort_order)->toBe(0);
});

test('product can be updated', function () {
    $product = Product::factory()->create(['name' => 'Old Name']);

    $updateData = [
        'name' => 'Updated Product Name',
        'description' => 'Updated Description',
        'original_price' => 200000,
        'selling_price' => 150000,
        'weight' => 600,
        'is_active' => true,
    ];

    $updatedProduct = $this->service->updateProduct($product, $updateData);

    expect($updatedProduct->name)->toBe('Updated Product Name')
        ->and($updatedProduct->slug)->toBe('updated-product-name')
        ->and((float)$updatedProduct->selling_price)->toBe(150000.0);
});

test('product can be updated with new images', function () {
    $product = Product::factory()->create();
    ProductImage::factory()->forProduct($product)->create();

    expect($product->images)->toHaveCount(1);

    $newImages = [
        UploadedFile::fake()->image('new1.jpg'),
        UploadedFile::fake()->image('new2.jpg'),
    ];

    $updateData = [
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'original_price' => 200000,
        'selling_price' => 150000,
        'weight' => 600,
    ];

    $updatedProduct = $this->service->updateProduct($product, $updateData, $newImages);

    expect($updatedProduct->images)->toHaveCount(3);
});

test('product can be deleted', function () {
    $product = Product::factory()->create();

    $result = $this->service->deleteProduct($product);

    expect($result)->toBeTrue()
        ->and(Product::withTrashed()->find($product->id)->trashed())->toBeTrue();
});

test('product images are deleted from storage when product is deleted', function () {
    $product = Product::factory()->create();
    $images = [
        UploadedFile::fake()->image('product1.jpg'),
        UploadedFile::fake()->image('product2.jpg'),
    ];

    $data = [
        'name' => 'Product to Delete',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
        'category_id' => $product->category_id,
    ];

    $productWithImages = $this->service->createProduct($data, $images);
    $imagePaths = $productWithImages->images->pluck('image_path')->toArray();

    // Verify images exist before deletion
    foreach ($imagePaths as $path) {
        expect(Storage::disk('public')->exists($path))->toBeTrue();
    }

    $this->service->deleteProduct($productWithImages);

    // Verify images are deleted after product deletion
    foreach ($imagePaths as $path) {
        expect(Storage::disk('public')->exists($path))->toBeFalse();
    }
});

test('single product image can be deleted', function () {
    $product = Product::factory()->create();
    $image = ProductImage::factory()->forProduct($product)->create([
        'image_path' => UploadedFile::fake()->image('test.jpg')->store('products', 'public'),
    ]);

    expect(Storage::disk('public')->exists($image->image_path))->toBeTrue();

    $result = $this->service->deleteImage($image);

    expect($result)->toBeTrue()
        ->and(Storage::disk('public')->exists($image->image_path))->toBeFalse()
        ->and(ProductImage::find($image->id))->toBeNull();
});

test('primary image can be changed', function () {
    $product = Product::factory()->create();
    $image1 = ProductImage::factory()->forProduct($product)->primary()->create();
    $image2 = ProductImage::factory()->forProduct($product)->create();

    expect($image1->is_primary)->toBeTrue()
        ->and($image2->is_primary)->toBeFalse();

    $this->service->setPrimaryImage($product, $image2);

    $image1->refresh();
    $image2->refresh();

    expect($image1->is_primary)->toBeFalse()
        ->and($image2->is_primary)->toBeTrue();
});

test('product images can be reordered', function () {
    $product = Product::factory()->create();
    $image1 = ProductImage::factory()->forProduct($product)->create(['sort_order' => 0]);
    $image2 = ProductImage::factory()->forProduct($product)->create(['sort_order' => 1]);
    $image3 = ProductImage::factory()->forProduct($product)->create(['sort_order' => 2]);

    $newOrder = [
        0 => $image3->id,
        1 => $image1->id,
        2 => $image2->id,
    ];

    $this->service->reorderImages($product, $newOrder);

    $image1->refresh();
    $image2->refresh();
    $image3->refresh();

    expect($image3->sort_order)->toBe(0)
        ->and($image1->sort_order)->toBe(1)
        ->and($image2->sort_order)->toBe(2);
});

test('product is_active defaults to false', function () {
    $category = Category::factory()->create();

    $data = [
        'category_id' => $category->id,
        'name' => 'Test Product',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
    ];

    $product = $this->service->createProduct($data);

    expect($product->is_active)->toBeFalse();
});

test('product is_featured defaults to false', function () {
    $category = Category::factory()->create();

    $data = [
        'category_id' => $category->id,
        'name' => 'Test Product',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
    ];

    $product = $this->service->createProduct($data);

    expect($product->is_featured)->toBeFalse();
});

test('product creation rolls back on failure', function () {
    $data = [
        'category_id' => 99999, // Non-existent category
        'name' => 'Test Product',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
    ];

    try {
        $this->service->createProduct($data);
    } catch (\Exception $e) {
        // Expected to fail
    }

    expect(Product::count())->toBe(0);
})->throws(\Exception::class);

test('product relationship with category works', function () {
    $category = Category::factory()->create(['name' => 'Electronics']);
    $product = Product::factory()->forCategory($category)->create();

    expect($product->category)->not->toBeNull()
        ->and($product->category->name)->toBe('Electronics')
        ->and($product->category->id)->toBe($category->id);
});

test('product can have marketplace urls', function () {
    $category = Category::factory()->create();

    $data = [
        'category_id' => $category->id,
        'name' => 'Product with Links',
        'description' => 'Test Description',
        'original_price' => 150000,
        'selling_price' => 120000,
        'weight' => 500,
        'tokopedia_url' => 'https://tokopedia.com/product',
        'shopee_url' => 'https://shopee.com/product',
        'tiktok_url' => 'https://tiktok.com/product',
    ];

    $product = $this->service->createProduct($data);

    expect($product->tokopedia_url)->toBe('https://tokopedia.com/product')
        ->and($product->shopee_url)->toBe('https://shopee.com/product')
        ->and($product->tiktok_url)->toBe('https://tiktok.com/product');
});

test('product images are ordered by sort_order', function () {
    $product = Product::factory()->create();
    $image3 = ProductImage::factory()->forProduct($product)->create(['sort_order' => 2]);
    $image1 = ProductImage::factory()->forProduct($product)->create(['sort_order' => 0]);
    $image2 = ProductImage::factory()->forProduct($product)->create(['sort_order' => 1]);

    $orderedImages = $product->fresh()->images;

    expect($orderedImages[0]->id)->toBe($image1->id)
        ->and($orderedImages[1]->id)->toBe($image2->id)
        ->and($orderedImages[2]->id)->toBe($image3->id);
});
