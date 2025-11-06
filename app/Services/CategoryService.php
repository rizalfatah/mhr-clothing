<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Create a new category with optional image
     */
    public function createCategory(array $data, ?UploadedFile $image = null): Category
    {
        DB::beginTransaction();
        try {
            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? false;
            $data['sort_order'] = $data['sort_order'] ?? 0;

            // Handle image upload
            if ($image) {
                $data['image'] = $this->uploadImage($image);
            }

            $category = Category::create($data);

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update existing category with optional new image
     */
    public function updateCategory(Category $category, array $data, ?UploadedFile $image = null): Category
    {
        DB::beginTransaction();
        try {
            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? false;
            $data['sort_order'] = $data['sort_order'] ?? 0;

            // Handle image upload
            if ($image) {
                // Delete old image
                if ($category->image) {
                    $this->deleteImage($category->image);
                }

                $data['image'] = $this->uploadImage($image);
            }

            $category->update($data);

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete category if it has no products
     */
    public function deleteCategory(Category $category): bool
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                throw new \Exception('Kategori tidak dapat dihapus karena masih memiliki produk.');
            }

            // Delete image from storage
            if ($category->image) {
                $this->deleteImage($category->image);
            }

            return $category->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Upload category image
     */
    private function uploadImage(UploadedFile $image): string
    {
        return $image->store('categories', 'public');
    }

    /**
     * Delete image from storage
     */
    private function deleteImage(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }
}
