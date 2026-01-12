<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
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

        $recent_products = Product::with(['category', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'order_stats', 'recent_products'));
    }
}
