<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'primaryImage'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');

            $product = Product::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat produk: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $product->load(['category', 'images']);
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');

            $product->update($validated);

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $currentImageCount = $product->images()->count();
                
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $currentImageCount === 0 && $index === 0,
                        'sort_order' => $currentImageCount + $index,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function deleteImage(ProductImage $image)
    {
        try {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            return back()->with('success', 'Gambar berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus gambar: ' . $e->getMessage());
        }
    }
}
