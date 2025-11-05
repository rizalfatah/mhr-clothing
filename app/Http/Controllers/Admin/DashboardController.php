<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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

        $recent_products = Product::with(['category', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_products'));
    }
}
