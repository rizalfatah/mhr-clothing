<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function createProduct(array $data, ?array $images = null): Product
    {
        DB::beginTransaction();
        try {
            // Extract variants from data
            $variants = $data['variants'] ?? null;
            unset($data['variants']);

            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? false;
            $data['is_featured'] = $data['is_featured'] ?? false;

            $product = Product::create($data);

            if ($images) {
                $this->uploadImages($product, $images);
            }

            // Sync variants
            $this->syncVariants($product, $variants);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update existing product with optional new images
     */
    public function updateProduct(Product $product, array $data, ?array $images = null): Product
    {
        DB::beginTransaction();
        try {
            // Extract variants from data
            $variants = $data['variants'] ?? null;
            unset($data['variants']);

            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? false;
            $data['is_featured'] = $data['is_featured'] ?? false;

            $product->update($data);

            if ($images) {
                $this->uploadImages($product, $images, false);
            }

            // Sync variants
            $this->syncVariants($product, $variants);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete product and all associated images
     */
    public function deleteProduct(Product $product): bool
    {
        try {
            // Delete images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            return $product->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a single product image
     */
    public function deleteImage(ProductImage $image): bool
    {
        try {
            Storage::disk('public')->delete($image->image_path);
            return $image->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Upload and attach images to product
     */
    private function uploadImages(Product $product, array $images, bool $isFirstPrimary = true): void
    {
        $currentImageCount = $isFirstPrimary ? 0 : $product->images()->count();

        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => $currentImageCount === 0 && $index === 0,
                'sort_order' => $currentImageCount + $index,
            ]);
        }
    }

    /**
     * Set image as primary for a product
     */
    public function setPrimaryImage(Product $product, ProductImage $image): void
    {
        DB::beginTransaction();
        try {
            // Remove primary status from all images
            $product->images()->update(['is_primary' => false]);

            // Set new primary image
            $image->update(['is_primary' => true]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reorder product images
     */
    public function reorderImages(Product $product, array $imageOrder): void
    {
        DB::beginTransaction();
        try {
            foreach ($imageOrder as $order => $imageId) {
                ProductImage::where('product_id', $product->id)
                    ->where('id', $imageId)
                    ->update(['sort_order' => $order]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Sync product variants
     */
    public function syncVariants(Product $product, ?array $variants = null): void
    {
        if (!$variants) {
            return;
        }

        DB::beginTransaction();
        try {
            // Get existing variant IDs from the input
            $inputVariantIds = collect($variants)->pluck('id')->filter()->toArray();

            // Delete variants not in the input
            $product->variants()->whereNotIn('id', $inputVariantIds)->delete();

            // Create or update variants
            foreach ($variants as $variantData) {
                if (isset($variantData['id']) && $variantData['id']) {
                    // Update existing variant
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'size' => $variantData['size'],
                            'sku' => $variantData['sku'] ?? null,
                            'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                            'stock' => $variantData['stock'] ?? 0,
                            'is_available' => $variantData['is_available'] ?? true,
                        ]);
                    }
                } else {
                    // Create new variant
                    $product->variants()->create([
                        'size' => $variantData['size'],
                        'sku' => $variantData['sku'] ?? null,
                        'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                        'stock' => $variantData['stock'] ?? 0,
                        'is_available' => $variantData['is_available'] ?? true,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
