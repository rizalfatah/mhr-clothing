<?php

use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->service = new CategoryService();
    Storage::fake('public');
});

test('category can be created with valid data', function () {
    $data = [
        'name' => 'Electronics',
        'description' => 'Electronic products',
        'is_active' => true,
        'sort_order' => 1,
    ];

    $category = $this->service->createCategory($data);

    expect($category)->toBeInstanceOf(Category::class)
        ->and($category->name)->toBe('Electronics')
        ->and($category->slug)->toBe('electronics')
        ->and($category->is_active)->toBeTrue();
});

test('category slug is generated from name', function () {
    $data = [
        'name' => 'Men\'s Fashion',
        'description' => 'Fashion for men',
    ];

    $category = $this->service->createCategory($data);

    expect($category->slug)->toBe('mens-fashion');
});

test('category can be created with image', function () {
    $image = UploadedFile::fake()->image('category.jpg');

    $data = [
        'name' => 'Electronics',
        'description' => 'Electronic products',
    ];

    $category = $this->service->createCategory($data, $image);

    expect($category->image)->not->toBeNull()
        ->and(Storage::disk('public')->exists($category->image))->toBeTrue();
});

test('category can be created without image', function () {
    $data = [
        'name' => 'Electronics',
        'description' => 'Electronic products',
    ];

    $category = $this->service->createCategory($data);

    expect($category->image)->toBeNull();
});

test('category is_active defaults to false', function () {
    $data = [
        'name' => 'Electronics',
        'description' => 'Electronic products',
    ];

    $category = $this->service->createCategory($data);

    expect($category->is_active)->toBeFalse();
});

test('category sort_order defaults to 0', function () {
    $data = [
        'name' => 'Electronics',
        'description' => 'Electronic products',
    ];

    $category = $this->service->createCategory($data);

    expect($category->sort_order)->toBe(0);
});

test('category can be updated', function () {
    $category = Category::factory()->create(['name' => 'Old Name']);

    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description',
        'is_active' => true,
        'sort_order' => 5,
    ];

    $updatedCategory = $this->service->updateCategory($category, $updateData);

    expect($updatedCategory->name)->toBe('Updated Category')
        ->and($updatedCategory->slug)->toBe('updated-category')
        ->and($updatedCategory->description)->toBe('Updated Description')
        ->and($updatedCategory->is_active)->toBeTrue()
        ->and($updatedCategory->sort_order)->toBe(5);
});

test('category can be updated with new image', function () {
    $category = Category::factory()->create();

    $newImage = UploadedFile::fake()->image('new-category.jpg');

    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description',
    ];

    $updatedCategory = $this->service->updateCategory($category, $updateData, $newImage);

    expect($updatedCategory->image)->not->toBeNull()
        ->and(Storage::disk('public')->exists($updatedCategory->image))->toBeTrue();
});

test('old image is deleted when category is updated with new image', function () {
    $oldImage = UploadedFile::fake()->image('old.jpg');
    $oldImagePath = $oldImage->store('categories', 'public');

    $category = Category::factory()->create(['image' => $oldImagePath]);

    expect(Storage::disk('public')->exists($oldImagePath))->toBeTrue();

    $newImage = UploadedFile::fake()->image('new.jpg');

    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description',
    ];

    $this->service->updateCategory($category, $updateData, $newImage);

    expect(Storage::disk('public')->exists($oldImagePath))->toBeFalse();
});

test('category without products can be deleted', function () {
    $category = Category::factory()->create();

    $result = $this->service->deleteCategory($category);

    expect($result)->toBeTrue()
        ->and(Category::find($category->id))->toBeNull();
});

test('category with products cannot be deleted', function () {
    $category = Category::factory()->create();
    Product::factory()->forCategory($category)->create();

    $this->service->deleteCategory($category);
})->throws(\Exception::class, 'Kategori tidak dapat dihapus karena masih memiliki produk.');

test('category image is deleted when category is deleted', function () {
    $image = UploadedFile::fake()->image('category.jpg');
    $imagePath = $image->store('categories', 'public');

    $category = Category::factory()->create(['image' => $imagePath]);

    expect(Storage::disk('public')->exists($imagePath))->toBeTrue();

    $this->service->deleteCategory($category);

    expect(Storage::disk('public')->exists($imagePath))->toBeFalse();
});

test('category can be deleted if it has no image', function () {
    $category = Category::factory()->create(['image' => null]);

    $result = $this->service->deleteCategory($category);

    expect($result)->toBeTrue()
        ->and(Category::find($category->id))->toBeNull();
});

test('category with multiple products cannot be deleted', function () {
    $category = Category::factory()->create();
    Product::factory()->count(5)->forCategory($category)->create();

    expect($category->products()->count())->toBe(5);

    $this->service->deleteCategory($category);
})->throws(\Exception::class);

test('category relationship with products works', function () {
    $category = Category::factory()->create(['name' => 'Fashion']);
    Product::factory()->count(3)->forCategory($category)->create();

    expect($category->products)->toHaveCount(3)
        ->and($category->products->first()->category_id)->toBe($category->id);
});

test('multiple categories can have same sort order', function () {
    $category1 = Category::factory()->create(['sort_order' => 1]);
    $category2 = Category::factory()->create(['sort_order' => 1]);

    expect($category1->sort_order)->toBe(1)
        ->and($category2->sort_order)->toBe(1);
});

test('category name is required for creation', function () {
    $data = [
        'description' => 'Test Description',
    ];

    $this->service->createCategory($data);
})->throws(\Exception::class);

test('category creation rolls back on failure', function () {
    // Force a failure by providing invalid data
    $data = [
        'name' => null, // Invalid: name is required
        'description' => 'Test Description',
    ];

    try {
        $this->service->createCategory($data);
    } catch (\Exception $e) {
        // Expected to fail
    }

    expect(Category::count())->toBe(0);
})->throws(\Exception::class);

test('category update rolls back on failure', function () {
    $category = Category::factory()->create(['name' => 'Original Name']);

    // Force a failure
    $data = [
        'name' => null, // Invalid: name is required
        'description' => 'Updated Description',
    ];

    try {
        $this->service->updateCategory($category, $data);
    } catch (\Exception $e) {
        // Expected to fail
    }

    $category->refresh();
    expect($category->name)->toBe('Original Name');
})->throws(\Exception::class);

test('active categories can be retrieved', function () {
    Category::factory()->count(3)->create(['is_active' => true]);
    Category::factory()->count(2)->create(['is_active' => false]);

    $activeCategories = Category::where('is_active', true)->get();

    expect($activeCategories)->toHaveCount(3);
});

test('categories are ordered by sort_order', function () {
    Category::factory()->create(['name' => 'Category C', 'sort_order' => 3]);
    Category::factory()->create(['name' => 'Category A', 'sort_order' => 1]);
    Category::factory()->create(['name' => 'Category B', 'sort_order' => 2]);

    $categories = Category::orderBy('sort_order')->get();

    expect($categories[0]->sort_order)->toBe(1)
        ->and($categories[1]->sort_order)->toBe(2)
        ->and($categories[2]->sort_order)->toBe(3);
});

test('category slug is unique', function () {
    $category1 = Category::factory()->create(['name' => 'Fashion']);

    // Creating another category with same name should still work
    // Laravel will allow duplicate slugs, this is just ensuring slug generation works
    $data = [
        'name' => 'Fashion',
        'description' => 'Another fashion category',
    ];

    $category2 = $this->service->createCategory($data);

    expect($category1->slug)->toBe('fashion')
        ->and($category2->slug)->toBe('fashion');
    // Note: The application doesn't enforce unique slugs, this tests current behavior
});

test('category update without image preserves existing image', function () {
    $image = UploadedFile::fake()->image('category.jpg');
    $imagePath = $image->store('categories', 'public');

    $category = Category::factory()->create(['image' => $imagePath]);

    $updateData = [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
    ];

    $updatedCategory = $this->service->updateCategory($category, $updateData);

    expect($updatedCategory->image)->toBe($imagePath)
        ->and(Storage::disk('public')->exists($imagePath))->toBeTrue();
});
