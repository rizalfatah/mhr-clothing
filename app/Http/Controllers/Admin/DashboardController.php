<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'featured_products' => Product::where('is_featured', true)->count(),
        ];

        // Order Statistics
        $order_stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'processing_orders' => Order::whereIn('status', [
                Order::STATUS_CONTACTED,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PAYMENT_PENDING,
                Order::STATUS_PAYMENT_CONFIRMED,
                Order::STATUS_PROCESSING
            ])->count(),
            'shipped_orders' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'completed_orders' => Order::whereIn('status', [
                Order::STATUS_DELIVERED,
                Order::STATUS_COMPLETED
            ])->count(),
        ];

        // Low Stock Threshold
        $low_stock_threshold = 10;

        // Inventory Statistics
        $inventory_stats = [
            'low_stock_count' => ProductVariant::where('stock', '>', 0)
                ->where('stock', '<=', $low_stock_threshold)
                ->count(),
            'out_of_stock_count' => ProductVariant::where('stock', 0)
                ->orWhere('is_available', false)
                ->count(),
        ];

        // Get products with low stock variants
        $low_stock_products = Product::with(['variants' => function ($query) use ($low_stock_threshold) {
            $query->where('stock', '>', 0)
                ->where('stock', '<=', $low_stock_threshold)
                ->orderBy('stock', 'asc');
        }, 'primaryImage'])
            ->whereHas('variants', function ($query) use ($low_stock_threshold) {
                $query->where('stock', '>', 0)
                    ->where('stock', '<=', $low_stock_threshold);
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        $recent_products = Product::with(['category', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'order_stats', 'inventory_stats', 'low_stock_products', 'recent_products'));
    }
}
