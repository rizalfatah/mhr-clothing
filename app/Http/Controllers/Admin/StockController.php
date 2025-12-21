<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category_id = $request->get('category_id');
        $status = $request->get('status');
        $sort = $request->get('sort', 'stock_asc'); // Default sort: Lowest stock first

        $query = ProductVariant::with(['product', 'product.category'])
            ->whereHas('product', function ($q) {
                $q->whereNull('deleted_at'); // Ensure product is not soft-deleted
            });

        // Search by Product Name or SKU
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                })->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($category_id) {
            $query->whereHas('product', function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            });
        }

        // Filter by Stock Status
        if ($status) {
            if ($status === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($status === 'low_stock') {
                $query->where('stock', '<', 5)->where('stock', '>', 0);
            } elseif ($status === 'in_stock') {
                $query->where('stock', '>=', 5);
            }
        }

        // Sorting
        switch ($sort) {
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'name_asc':
                $query->join('products', 'product_variants.product_id', '=', 'products.id')
                    ->orderBy('products.name', 'asc')
                    ->select('product_variants.*'); // Avoid column conflicts
                break;
            case 'name_desc':
                $query->join('products', 'product_variants.product_id', '=', 'products.id')
                    ->orderBy('products.name', 'desc')
                    ->select('product_variants.*');
                break;
            default:
                $query->orderBy('stock', 'asc');
                break;
        }

        $stocks = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.stock.index', compact('stocks', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $variant = ProductVariant::findOrFail($id);

        $oldStock = $variant->stock;
        $newStock = $request->stock;

        $variant->stock = $newStock;

        // Auto-update availability based on stock
        if ($newStock > 0 && !$variant->is_available) {
            $variant->is_available = true;
        } elseif ($newStock == 0) {
            // Might want to keep it available but out of stock, or auto-disable.
            // For now, let's strictly follow the model logic or specific requirements if any.
            // The model `decrementStock` auto-disables if 0. Let's keep consistent behavior?
            // Actually, for admin manual update, let's just checking stock 0
            if ($newStock == 0) {
                $variant->is_available = false;
            }
        }

        $variant->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Stok berhasil diperbarui.",
                'new_stock' => $variant->stock,
                'variant_id' => $variant->id
            ]);
        }

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }
}
