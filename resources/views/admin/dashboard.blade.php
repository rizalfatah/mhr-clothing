@extends('admin.layouts.app')

@section('title', 'Dasboard')
@section('breadcrumb', 'Dasboard')

@section('content')
    <!-- Page Heading -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Dasboard</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Selamat datang kembali, {{ auth()->user()->name }}!
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-6">
        <!-- Total Products -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center gap-x-3">
                <div class="shrink-0">
                    <span
                        class="inline-flex items-center justify-center size-12 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
                        <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </span>
                </div>
                <div class="grow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Produk</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        {{ number_format($stats['total_products']) }}</h3>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center gap-x-3">
                <div class="shrink-0">
                    <span
                        class="inline-flex items-center justify-center size-12 rounded-lg bg-green-100 text-green-600 dark:bg-green-800/30 dark:text-green-500">
                        <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="grow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Produk Aktif</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        {{ number_format($stats['active_products']) }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center gap-x-3">
                <div class="shrink-0">
                    <span
                        class="inline-flex items-center justify-center size-12 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-800/30 dark:text-purple-500">
                        <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </span>
                </div>
                <div class="grow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Kategori</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        {{ number_format($stats['total_categories']) }}</h3>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center gap-x-3">
                <div class="shrink-0">
                    <span
                        class="inline-flex items-center justify-center size-12 rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-800/30 dark:text-yellow-500">
                        <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                    </span>
                </div>
                <div class="grow">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Produk Unggulan</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        {{ number_format($stats['featured_products']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- End Stats Grid -->

    <!-- Order Statistics Section -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 mb-4">Order Statistics</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
            <!-- Total Orders -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center gap-x-3">
                    <div class="shrink-0">
                        <span
                            class="inline-flex items-center justify-center size-12 rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-800/30 dark:text-indigo-500">
                            <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="grow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Orders</p>
                        <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            {{ number_format($order_stats['total_orders']) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center gap-x-3">
                    <div class="shrink-0">
                        <span
                            class="inline-flex items-center justify-center size-12 rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-800/30 dark:text-yellow-500">
                            <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </span>
                    </div>
                    <div class="grow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Pending Orders</p>
                        <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            {{ number_format($order_stats['pending_orders']) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Processing Orders -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center gap-x-3">
                    <div class="shrink-0">
                        <span
                            class="inline-flex items-center justify-center size-12 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-800/30 dark:text-purple-500">
                            <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"></path>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="grow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Processing</p>
                        <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            {{ number_format($order_stats['processing_orders']) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Shipped Orders -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center gap-x-3">
                    <div class="shrink-0">
                        <span
                            class="inline-flex items-center justify-center size-12 rounded-lg bg-cyan-100 text-cyan-600 dark:bg-cyan-800/30 dark:text-cyan-500">
                            <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </span>
                    </div>
                    <div class="grow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Shipped</p>
                        <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            {{ number_format($order_stats['shipped_orders']) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Completed Orders -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center gap-x-3">
                    <div class="shrink-0">
                        <span
                            class="inline-flex items-center justify-center size-12 rounded-lg bg-green-100 text-green-600 dark:bg-green-800/30 dark:text-green-500">
                            <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </span>
                    </div>
                    <div class="grow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Completed</p>
                        <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            {{ number_format($order_stats['completed_orders']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Order Statistics Section -->

        <!-- Revenue Statistics Section -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 mb-4">Revenue Overview</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
                <!-- Today's Revenue -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-800/30 dark:text-emerald-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Today</p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($revenue_stats['today_revenue'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- This Week's Revenue -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                    </rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">This Week</p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($revenue_stats['this_week_revenue'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- This Month's Revenue -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-800/30 dark:text-violet-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3v18h18"></path>
                                    <path d="m19 9-5 5-4-4-3 3"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">This Month</p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($revenue_stats['this_month_revenue'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-800/30 dark:text-amber-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Revenue
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($revenue_stats['total_revenue'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-800/30 dark:text-rose-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                                    <path d="M3 6h18"></path>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Avg Order Value
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($revenue_stats['average_order_value'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Revenue Statistics Section -->

        <!-- Charts Section -->
        <div class="mt-6 grid lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Revenue Chart (Line Chart) - Takes 2 columns -->
            <div
                class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="p-4 md:p-5">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Revenue Trend</h3>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Last 30 days revenue overview</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="dailyChartBtn"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-600 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                                Daily
                            </button>
                            <button type="button" id="monthlyChartBtn"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700">
                                Monthly
                            </button>
                        </div>
                    </div>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Status Chart (Doughnut Chart) - Takes 1 column -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="p-4 md:p-5">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Order Distribution</h3>
                        <p class="text-sm text-gray-600 dark:text-neutral-400">Orders by status</p>
                    </div>
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <!-- Chart Legend -->
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        @foreach ($chartData['orderStatus'] as $status)
                            <div class="flex items-center gap-x-2">
                                <span class="size-3 rounded-full"
                                    style="background-color: {{ $status['color'] }}"></span>
                                <span class="text-sm text-gray-600 dark:text-neutral-400">{{ $status['status'] }}
                                    ({{ $status['count'] }})
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- End Charts Section -->

        <!-- Inventory Statistics Section -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 mb-4">Inventory Alerts</h2>
            <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- Low Stock Count -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-orange-100 text-orange-600 dark:bg-orange-800/30 dark:text-orange-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                    </path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Low Stock
                                Variants
                            </p>
                            <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                {{ number_format($inventory_stats['low_stock_count']) }}</h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-500">Stock ≤ 10 units</p>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock Count -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex items-center gap-x-3">
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center justify-center size-12 rounded-lg bg-red-100 text-red-600 dark:bg-red-800/30 dark:text-red-500">
                                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="grow">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Out of Stock
                                Variants</p>
                            <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                {{ number_format($inventory_stats['out_of_stock_count']) }}</h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-500">Needs restocking</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Inventory Statistics Section -->

        <!-- Low Stock Products Alert Table -->
        @if ($low_stock_products->count() > 0)
            <div class="mt-6">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div
                                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                                <!-- Header -->
                                <div
                                    class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                            Low Stock Alert
                                        </h2>
                                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                                            Products with variants running low on stock (≤ 10 units)
                                        </p>
                                    </div>

                                    <div>
                                        <a href="{{ route('admin.products.index') }}"
                                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                            View All Products
                                        </a>
                                    </div>
                                </div>
                                <!-- End Header -->

                                <!-- Table -->
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-800">
                                        <tr>
                                            <th scope="col" class="ps-6 py-3 text-start">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Product</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Variant</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Stock</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Status</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-end">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Action</span>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                        @foreach ($low_stock_products as $product)
                                            @foreach ($product->variants as $variant)
                                                <tr>
                                                    <td class="size-px whitespace-nowrap">
                                                        <div class="ps-6 py-3">
                                                            <div class="flex items-center gap-x-3">
                                                                @if ($product->primaryImage)
                                                                    <img class="inline-block size-[38px] rounded-lg object-cover"
                                                                        src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                                        alt="{{ $product->name }}">
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center justify-center size-[38px] rounded-lg bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                                                        <svg class="shrink-0 size-5"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <rect width="18" height="18" x="3"
                                                                                y="3" rx="2" ry="2" />
                                                                            <circle cx="9" cy="9" r="2" />
                                                                            <path
                                                                                d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                                <div class="grow">
                                                                    <span
                                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="size-px whitespace-nowrap">
                                                        <div class="px-6 py-3">
                                                            <span class="text-sm text-gray-600 dark:text-neutral-400">
                                                                @if ($variant->size && $variant->color)
                                                                    {{ $variant->size }} / {{ $variant->color }}
                                                                @elseif($variant->size)
                                                                    {{ $variant->size }}
                                                                @elseif($variant->color)
                                                                    {{ $variant->color }}
                                                                @else
                                                                    Default
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="size-px whitespace-nowrap">
                                                        <div class="px-6 py-3">
                                                            <span
                                                                class="inline-flex items-center gap-1.5 py-1 px-2 rounded-lg text-xs font-medium 
                                                            @if ($variant->stock <= 3) bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-500
                                                            @elseif($variant->stock <= 5) bg-orange-100 text-orange-800 dark:bg-orange-500/10 dark:text-orange-500
                                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-500 @endif">
                                                                {{ $variant->stock }} units
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="size-px whitespace-nowrap">
                                                        <div class="px-6 py-3">
                                                            @if ($variant->stock <= 3)
                                                                <span
                                                                    class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                                    <svg class="size-3" xmlns="http://www.w3.org/2000/svg"
                                                                        width="16" height="16" fill="currentColor"
                                                                        viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                                    </svg>
                                                                    Critical
                                                                </span>
                                                            @elseif($variant->stock <= 5)
                                                                <span
                                                                    class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full dark:bg-orange-500/10 dark:text-orange-500">
                                                                    <svg class="size-3" xmlns="http://www.w3.org/2000/svg"
                                                                        width="16" height="16" fill="currentColor"
                                                                        viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                                    </svg>
                                                                    Urgent
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                                                                    <svg class="size-3" xmlns="http://www.w3.org/2000/svg"
                                                                        width="16" height="16" fill="currentColor"
                                                                        viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                                        <path
                                                                            d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                                                                    </svg>
                                                                    Low
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="size-px whitespace-nowrap">
                                                        <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                                class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                                                Restock
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- End Table -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- End Low Stock Products Alert Table -->

        <!-- Recent Products -->
        <div class="mt-6">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div
                            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                            <!-- Header -->
                            <div
                                class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                        Produk Terbaru
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                                        Produk terbaru yang ditambahkan ke toko Anda
                                    </p>
                                </div>

                                <div>
                                    <a href="{{ route('admin.products.index') }}"
                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                        Lihat Semua
                                    </a>
                                </div>
                            </div>
                            <!-- End Header -->

                            <!-- Table -->
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col" class="ps-6 py-3 text-start">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Produk</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Kategori</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Harga</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Status</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-end">
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    @forelse($recent_products as $product)
                                        <tr>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="ps-6 py-3">
                                                    <div class="flex items-center gap-x-3">
                                                        @if ($product->primaryImage)
                                                            <img class="inline-block size-[38px] rounded-lg object-cover"
                                                                src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                                alt="{{ $product->name }}">
                                                        @else
                                                            <span
                                                                class="inline-flex items-center justify-center size-[38px] rounded-lg bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                                                <svg class="shrink-0 size-5"
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect width="18" height="18" x="3" y="3"
                                                                        rx="2" ry="2" />
                                                                    <circle cx="9" cy="9" r="2" />
                                                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                        <div class="grow">
                                                            <span
                                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</span>
                                                            <span
                                                                class="block text-sm text-gray-500 dark:text-neutral-500">{{ Str::limit($product->description, 50) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <span
                                                        class="text-sm text-gray-600 dark:text-neutral-400">{{ $product->category->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <span
                                                        class="text-sm font-medium text-gray-800 dark:text-neutral-200">Rp
                                                        {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    @if ($product->is_active)
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg"
                                                                width="16" height="16" fill="currentColor"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                            </svg>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-white/10 dark:text-white">
                                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg"
                                                                width="16" height="16" fill="currentColor"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                            </svg>
                                                            Nonaktif
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                                    <a href="{{ route('admin.products.edit', $product) }}"
                                                        class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                                        Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3 text-center">
                                                    <span class="text-sm text-gray-600 dark:text-neutral-400">Tidak ada
                                                        produk</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Recent Products -->
    @endsection

    @push('scripts')
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Chart Data from Controller
                const dailyRevenueData = @json($chartData['dailyRevenue']);
                const monthlyRevenueData = @json($chartData['monthlyRevenue']);
                const orderStatusData = @json($chartData['orderStatus']);

                // Format number to Indonesian currency
                function formatCurrency(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                }

                // Revenue Chart (Line Chart)
                const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                let revenueChart = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: dailyRevenueData.map(item => item.date),
                        datasets: [{
                            label: 'Revenue',
                            data: dailyRevenueData.map(item => item.revenue),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return formatCurrency(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        }
                    }
                });

                // Toggle buttons for Daily/Monthly view
                const dailyBtn = document.getElementById('dailyChartBtn');
                const monthlyBtn = document.getElementById('monthlyChartBtn');

                dailyBtn.addEventListener('click', function() {
                    // Update chart data
                    revenueChart.data.labels = dailyRevenueData.map(item => item.date);
                    revenueChart.data.datasets[0].data = dailyRevenueData.map(item => item.revenue);
                    revenueChart.update();

                    // Update button styles
                    dailyBtn.classList.remove('border-gray-200', 'bg-white', 'text-gray-800',
                        'dark:bg-neutral-800',
                        'dark:border-neutral-700', 'dark:text-white');
                    dailyBtn.classList.add('border-blue-600', 'bg-blue-600', 'text-white');

                    monthlyBtn.classList.remove('border-blue-600', 'bg-blue-600', 'text-white');
                    monthlyBtn.classList.add('border-gray-200', 'bg-white', 'text-gray-800',
                        'dark:bg-neutral-800',
                        'dark:border-neutral-700', 'dark:text-white');

                    // Update description
                    document.querySelector('.lg\\:col-span-2 p').textContent = 'Last 30 days revenue overview';
                });

                monthlyBtn.addEventListener('click', function() {
                    // Update chart data
                    revenueChart.data.labels = monthlyRevenueData.map(item => item.month);
                    revenueChart.data.datasets[0].data = monthlyRevenueData.map(item => item.revenue);
                    revenueChart.update();

                    // Update button styles
                    monthlyBtn.classList.remove('border-gray-200', 'bg-white', 'text-gray-800',
                        'dark:bg-neutral-800',
                        'dark:border-neutral-700', 'dark:text-white');
                    monthlyBtn.classList.add('border-blue-600', 'bg-blue-600', 'text-white');

                    dailyBtn.classList.remove('border-blue-600', 'bg-blue-600', 'text-white');
                    dailyBtn.classList.add('border-gray-200', 'bg-white', 'text-gray-800',
                        'dark:bg-neutral-800',
                        'dark:border-neutral-700', 'dark:text-white');

                    // Update description
                    document.querySelector('.lg\\:col-span-2 p').textContent =
                    'Last 12 months revenue overview';
                });

                // Order Status Chart (Doughnut Chart)
                const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
                new Chart(orderStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: orderStatusData.map(item => item.status),
                        datasets: [{
                            data: orderStatusData.map(item => item.count),
                            backgroundColor: orderStatusData.map(item => item.color),
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100)
                                            .toFixed(1) :
                                            0;
                                        return `${context.label}: ${context.parsed} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
