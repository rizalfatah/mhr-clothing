<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        $products = Product::with(['category', 'primaryImage'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
        // Check for duplicate product name/slug first
        if ($request->has('name')) {
            $slug = Str::slug($request->name);
            $existingProduct = Product::where('slug', $slug)->first();

            if ($existingProduct) {
                return back()->withInput()
                    ->with('error', 'Nama produk "' . $request->name . '" sudah ada. Silakan gunakan nama yang berbeda.');
            }
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'tokopedia_url' => 'nullable|url',
            'shopee_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:4096',
        ], [
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus: jpeg, jpg, png, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');

            $images = $request->hasFile('images') ? $request->file('images') : null;

            $this->productService->createProduct($validated, $images);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dibuat.');
        } catch (\Exception $e) {
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
        // Check for duplicate product name/slug (excluding current product)
        if ($request->has('name')) {
            $slug = Str::slug($request->name);
            $existingProduct = Product::where('slug', $slug)
                ->where('id', '!=', $product->id)
                ->first();

            if ($existingProduct) {
                return back()->withInput()
                    ->with('error', 'Nama produk "' . $request->name . '" sudah ada. Silakan gunakan nama yang berbeda.');
            }
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'tokopedia_url' => 'nullable|url',
            'shopee_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:4096',
        ], [
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus: jpeg, jpg, png, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');

            $images = $request->hasFile('images') ? $request->file('images') : null;

            $this->productService->updateProduct($product, $validated, $images);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product, Request $request)
    {
        try {
            $this->productService->deleteProduct($product);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function deleteImage(ProductImage $productImage, Request $request)
    {
        try {
            $this->productService->deleteImage($productImage);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus.'
                ]);
            }

            return back()->with('success', 'Gambar berhasil dihapus.');
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus gambar'
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus gambar');
        }
    }
}
