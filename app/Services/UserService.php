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
        $user->load(['orders.items', 'addresses']);

        // Calculate user statistics
        $userStats = [
            'total_orders' => $user->orders->count(),
            'total_spent' => $user->orders->sum('total'),
            'average_order_value' => $user->orders->count() > 0
                ? $user->orders->avg('total')
                : 0,
            'first_order_date' => $user->orders->min('created_at'),
            'last_order_date' => $user->orders->max('created_at'),
        ];

        // Get recent orders
        $recentOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(10)
            ->get();

        return [
            'user' => $user,
            'userStats' => $userStats,
            'recentOrders' => $recentOrders,
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
}
