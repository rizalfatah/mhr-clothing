<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get filtered and paginated users
     */
    public function getUsers(array $filters): LengthAwarePaginator
    {
        $query = User::query();

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($filters['per_page'] ?? 15)
            ->withQueryString();
    }

    /**
     * Get filtered and paginated admin users
     */
    public function getAdmins(array $filters): LengthAwarePaginator
    {
        $query = User::where('role', 'admin');

        $this->applySearch($query, $filters['search'] ?? null);

        return $query->latest()
            ->paginate($filters['per_page'] ?? 15)
            ->withQueryString();
    }

    /**
     * Get filtered and paginated customer users
     */
    public function getCustomers(array $filters): LengthAwarePaginator
    {
        $query = User::where('role', 'customer');

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($filters['per_page'] ?? 15)
            ->withQueryString();
    }

    /**
     * Get user with detailed information
     */
    public function getUserDetails(User $user): array
    {
        // Load relationships
        $user->load(['orders.items.product', 'addresses', 'cartItems.product']);

        // Calculate user statistics
        $userStats = [
            'total_orders' => $user->orders->count(),
            'total_spent' => $user->orders->sum('total'),
            'average_order_value' => $user->orders->count() > 0
                ? $user->orders->avg('total')
                : 0,
            'first_order_date' => $user->orders->min('created_at'),
            'last_order_date' => $user->orders->max('created_at'),
            'order_frequency' => $this->getOrderFrequencyMetrics($user),
        ];

        // Get recent orders
        $recentOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(10)
            ->get();

        // Get order status breakdown
        $orderStatusBreakdown = $this->getOrderStatusBreakdown($user);

        // Get shopping behavior analytics
        $shoppingBehavior = [
            'abandoned_cart' => $this->getAbandonedCartItems($user),
            'most_purchased_categories' => $this->getMostPurchasedCategories($user),
            'most_purchased_products' => $this->getMostPurchasedProducts($user),
            'favorite_variants' => $this->getFavoriteVariants($user),
        ];

        return [
            'user' => $user,
            'userStats' => $userStats,
            'recentOrders' => $recentOrders,
            'orderStatusBreakdown' => $orderStatusBreakdown,
            'shoppingBehavior' => $shoppingBehavior,
        ];
    }

    /**
     * Calculate overall user statistics
     */
    public function getUserStatistics(): array
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Active users - users who have ordered in the last 30 days
        $activeUsers = User::whereHas('orders', function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })->count();

        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $emailVerificationRate = $totalUsers > 0
            ? round(($verifiedUsers / $totalUsers) * 100, 1)
            : 0;

        $adminCount = User::where('role', 'admin')->count();
        $customerCount = User::where('role', 'customer')->count();

        return [
            'total_users' => $totalUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'active_users' => $activeUsers,
            'verified_users' => $verifiedUsers,
            'email_verification_rate' => $emailVerificationRate,
            'admin_count' => $adminCount,
            'customer_count' => $customerCount,
        ];
    }

    /**
     * Calculate customer-specific statistics
     */
    public function getCustomerStatistics(): array
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomersThisMonth = User::where('role', 'customer')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $activeCustomers = User::where('role', 'customer')
            ->whereHas('orders', function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            })->count();

        $verifiedCustomers = User::where('role', 'customer')
            ->whereNotNull('email_verified_at')
            ->count();

        $emailVerificationRate = $totalCustomers > 0
            ? round(($verifiedCustomers / $totalCustomers) * 100, 1)
            : 0;

        return [
            'total_customers' => $totalCustomers,
            'new_customers_this_month' => $newCustomersThisMonth,
            'active_customers' => $activeCustomers,
            'verified_customers' => $verifiedCustomers,
            'email_verification_rate' => $emailVerificationRate,
        ];
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        // Role filter
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        // Email verification filter
        if (!empty($filters['email_verified'])) {
            if ($filters['email_verified'] === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Search
        $this->applySearch($query, $filters['search'] ?? null);
    }

    /**
     * Apply search to the query
     */
    private function applySearch(Builder $query, ?string $search): void
    {
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Apply sorting to the query
     */
    private function applySorting(Builder $query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        // Add calculated fields for sorting
        if ($sortBy === 'orders_count' || $sortBy === 'total_spent') {
            $query->withCount('orders')
                ->withSum('orders', 'total');

            if ($sortBy === 'orders_count') {
                $query->orderBy('orders_count', $sortOrder);
            } else {
                $query->orderBy('orders_sum_total', $sortOrder);
            }
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
    }

    /**
     * Calculate order frequency metrics
     */
    private function getOrderFrequencyMetrics(User $user): array
    {
        if ($user->orders->count() === 0) {
            return [
                'orders_per_month' => 0,
                'days_between_orders' => 0,
            ];
        }

        $firstOrderDate = $user->orders->min('created_at');
        $lastOrderDate = $user->orders->max('created_at');

        if (!$firstOrderDate || !$lastOrderDate) {
            return [
                'orders_per_month' => 0,
                'days_between_orders' => 0,
            ];
        }

        $monthsDiff = \Carbon\Carbon::parse($firstOrderDate)
            ->diffInMonths(\Carbon\Carbon::parse($lastOrderDate));

        // If all orders in same month, use 1 month
        $monthsDiff = max($monthsDiff, 1);

        $ordersPerMonth = round($user->orders->count() / $monthsDiff, 2);

        // Calculate average days between orders
        $daysDiff = \Carbon\Carbon::parse($firstOrderDate)
            ->diffInDays(\Carbon\Carbon::parse($lastOrderDate));

        $daysBetweenOrders = $user->orders->count() > 1
            ? round($daysDiff / ($user->orders->count() - 1), 1)
            : 0;

        return [
            'orders_per_month' => $ordersPerMonth,
            'days_between_orders' => $daysBetweenOrders,
        ];
    }

    /**
     * Get order status breakdown
     */
    private function getOrderStatusBreakdown(User $user): array
    {
        $breakdown = $user->orders
            ->groupBy('status')
            ->map(fn($orders) => $orders->count())
            ->toArray();

        return $breakdown;
    }

    /**
     * Get abandoned cart items (items in cart for >24 hours)
     */
    private function getAbandonedCartItems(User $user): array
    {
        $abandonedItems = $user->cartItems
            ->filter(function ($item) {
                return $item->created_at->lt(now()->subHours(24));
            });

        $totalValue = $abandonedItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        return [
            'count' => $abandonedItems->count(),
            'total_value' => $totalValue,
            'items' => $abandonedItems->take(5), // Show top 5 abandoned items
        ];
    }

    /**
     * Get most purchased categories
     */
    private function getMostPurchasedCategories(User $user): array
    {
        // Get all order items with product relationships
        $orderItems = \App\Models\OrderItem::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('product.category')
            ->get();

        // Group by category and sum quantities
        $categories = $orderItems
            ->filter(fn($item) => $item->product && $item->product->category)
            ->groupBy(fn($item) => $item->product->category->id)
            ->map(function ($items) {
                $category = $items->first()->product->category;
                return [
                    'name' => $category->name,
                    'quantity' => $items->sum('quantity'),
                    'total_spent' => $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values()
            ->toArray();

        return $categories;
    }

    /**
     * Get most purchased products
     */
    private function getMostPurchasedProducts(User $user): array
    {
        // Get all order items
        $orderItems = \App\Models\OrderItem::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('product')
            ->get();

        // Group by product and sum quantities
        $products = $orderItems
            ->filter(fn($item) => $item->product)
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'quantity' => $items->sum('quantity'),
                    'total_spent' => $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values()
            ->toArray();

        return $products;
    }

    /**
     * Get favorite product variants (sizes, colors, etc.)
     */
    private function getFavoriteVariants(User $user): array
    {
        // Get all order items with variant information
        $orderItems = \App\Models\OrderItem::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereNotNull('variant_name')
            ->get();

        // Group by variant name and count
        $variants = $orderItems
            ->groupBy('variant_name')
            ->map(function ($items, $variantName) {
                return [
                    'name' => $variantName,
                    'count' => $items->count(),
                    'quantity' => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(10)
            ->values()
            ->toArray();

        return $variants;
    }
}
