<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('categories', 'public');
                $validated['image'] = $path;
            }

            Category::create($validated);

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat kategori: ' . $e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $path = $request->file('image')->store('categories', 'public');
                $validated['image'] = $path;
            }

            $category->update($validated);

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
            }

            // Delete image from storage
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
