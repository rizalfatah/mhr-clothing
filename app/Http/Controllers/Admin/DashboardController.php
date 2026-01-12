<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
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

        // Revenue Statistics (only from completed/delivered orders)
        $completedStatuses = [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED, Order::STATUS_PAYMENT_CONFIRMED];

        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfWeek = Carbon::now()->startOfWeek();

        $revenue_stats = [
            'today_revenue' => Order::whereIn('status', $completedStatuses)
                ->whereDate('created_at', $today)
                ->sum('total'),
            'this_week_revenue' => Order::whereIn('status', $completedStatuses)
                ->whereBetween('created_at', [$startOfWeek, Carbon::now()])
                ->sum('total'),
            'this_month_revenue' => Order::whereIn('status', $completedStatuses)
                ->whereBetween('created_at', [$startOfMonth, Carbon::now()])
                ->sum('total'),
            'total_revenue' => Order::whereIn('status', $completedStatuses)
                ->sum('total'),
            'average_order_value' => Order::whereIn('status', $completedStatuses)
                ->avg('total') ?? 0,
        ];

        // Daily Revenue for last 30 days (for chart)
        $dailyRevenueData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Order::whereIn('status', $completedStatuses)
                ->whereDate('created_at', $date)
                ->sum('total');
            $dailyRevenueData[] = [
                'date' => $date->format('d M'),
                'revenue' => (float) $revenue,
            ];
        }

        // Monthly Revenue for last 12 months (for chart)
        $monthlyRevenueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Order::whereIn('status', $completedStatuses)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $monthlyRevenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        // Order Status Distribution (for pie chart)
        $orderStatusData = [
            ['status' => 'Pending', 'count' => $order_stats['pending_orders'], 'color' => '#6b7280'],
            ['status' => 'Processing', 'count' => $order_stats['processing_orders'], 'color' => '#8b5cf6'],
            ['status' => 'Shipped', 'count' => $order_stats['shipped_orders'], 'color' => '#06b6d4'],
            ['status' => 'Completed', 'count' => $order_stats['completed_orders'], 'color' => '#10b981'],
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

        // Chart Data
        $chartData = [
            'dailyRevenue' => $dailyRevenueData,
            'monthlyRevenue' => $monthlyRevenueData,
            'orderStatus' => $orderStatusData,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'order_stats',
            'revenue_stats',
            'inventory_stats',
            'low_stock_products',
            'recent_products',
            'chartData'
        ));
    }
}
