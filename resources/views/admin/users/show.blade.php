@extends('admin.layouts.app')

@section('title', 'User Detail - ' . $user->name)
@section('breadcrumb', 'User Detail')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ $user->role === 'admin' ? route('admin.users.admins') : route('admin.users.customers') }}"
            class="inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 px-3 py-2 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700">
            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Back to {{ $user->role === 'admin' ? 'Admins' : 'Customers' }}
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Left Column - User Info -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Personal Information -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Personal Information
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Name</label>
                        <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">{{ $user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Email</label>
                        <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">WhatsApp</label>
                        <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">{{ $user->whatsapp_number }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Role</label>
                        <p class="mt-1">
                            <span
                                class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-500' : 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Email
                            Verification</label>
                        <p class="mt-1">
                            @if ($user->email_verified_at)
                                <span
                                    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">
                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500">
                                    Unverified
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Registered</label>
                        <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">
                            {{ $user->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-neutral-500">
                            {{ $user->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if ($user->last_login_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Last
                                Login</label>
                            <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">
                                {{ $user->last_login_at->format('d M Y, H:i') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-neutral-500">
                                {{ $user->last_login_at->diffForHumans() }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Account
                            Status</label>
                        <p class="mt-1">
                            <span
                                class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-{{ $user->account_status_color }}-100 text-{{ $user->account_status_color }}-800 dark:bg-{{ $user->account_status_color }}-800/30 dark:text-{{ $user->account_status_color }}-500">
                                @if ($user->account_status === 'active')
                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                    </svg>
                                @endif
                                {{ $user->account_status_label }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            @if ($user->role === 'customer')
                <!-- User Statistics -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            Statistics
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Total
                                Orders</label>
                            <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-neutral-200">
                                {{ number_format($userStats['total_orders']) }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Total
                                Spent</label>
                            <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($userStats['total_spent'], 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Average
                                Order Value</label>
                            <p class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Rp {{ number_format($userStats['average_order_value'], 0, ',', '.') }}
                            </p>
                        </div>

                        @if ($userStats['first_order_date'])
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">First
                                    Order</label>
                                <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">
                                    {{ \Carbon\Carbon::parse($userStats['first_order_date'])->format('d M Y') }}
                                </p>
                            </div>
                        @endif

                        @if ($userStats['last_order_date'])
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Last
                                    Order</label>
                                <p class="mt-1 text-sm text-gray-800 dark:text-neutral-200">
                                    {{ \Carbon\Carbon::parse($userStats['last_order_date'])->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-neutral-500">
                                    {{ \Carbon\Carbon::parse($userStats['last_order_date'])->diffForHumans() }}
                                </p>
                            </div>
                        @endif

                        @if (isset($userStats['order_frequency']))
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Order
                                    Frequency</label>
                                <p class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    {{ $userStats['order_frequency']['orders_per_month'] }} orders/month
                                </p>
                                @if ($userStats['order_frequency']['days_between_orders'] > 0)
                                    <p class="text-xs text-gray-500 dark:text-neutral-500">
                                        ~{{ round($userStats['order_frequency']['days_between_orders']) }} days between
                                        orders
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Orders & Addresses -->
        <div class="lg:col-span-2 space-y-4">
            @if ($user->role === 'customer')
                <!-- Recent Orders -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            Recent Orders
                        </h2>
                    </div>

                    @if ($recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Order Number</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Date</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Items</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Total</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    @foreach ($recentOrders as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                {{ $order->order_number }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                {{ $order->created_at->format('d M Y') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                {{ $order->items->count() }} item(s)
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                Rp {{ number_format($order->total, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800 dark:bg-{{ $order->status_color }}-800/30 dark:text-{{ $order->status_color }}-500">
                                                    {{ $order->status_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a href="{{ route('admin.transactions.show', $order) }}"
                                                    class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-neutral-500">
                            No orders yet.
                        </div>
                    @endif

                    @if (!empty($orderStatusBreakdown))
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-neutral-600">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 mb-3">Order Status
                                Summary</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($orderStatusBreakdown as $status => $count)
                                    @php
                                        $statusColor = match ($status) {
                                            'pending' => 'gray',
                                            'contacted' => 'blue',
                                            'confirmed' => 'indigo',
                                            'payment_pending' => 'yellow',
                                            'payment_confirmed' => 'green',
                                            'processing' => 'purple',
                                            'shipped' => 'cyan',
                                            'delivered' => 'teal',
                                            'completed' => 'emerald',
                                            'cancelled' => 'red',
                                            default => 'gray',
                                        };
                                        $statusLabel = \App\Models\Order::getStatuses()[$status] ?? ucfirst($status);
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-800/30 dark:text-{{ $statusColor }}-500">
                                        {{ $statusLabel }}: {{ $count }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Addresses -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            Saved Addresses
                        </h2>
                    </div>

                    @if ($user->addresses->count() > 0)
                        <div class="p-6 space-y-4">
                            @foreach ($user->addresses as $address)
                                <div class="p-4 border border-gray-200 rounded-lg dark:border-neutral-700">
                                    <div class="flex items-start justify-between mb-2">
                                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                            {{ $address->label }}
                                        </h3>
                                        @if ($address->is_default)
                                            <span
                                                class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                                        {{ $address->recipient_name }} - {{ $address->phone_number }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                                        {{ $address->address }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                                        {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-neutral-500">
                            No saved addresses.
                        </div>
                    @endif
                </div>

                <!-- Shopping Behavior -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            Shopping Behavior
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Abandoned Cart -->
                        @if (isset($shoppingBehavior['abandoned_cart']) && $shoppingBehavior['abandoned_cart']['count'] > 0)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 mb-3">
                                    ⚠️ Abandoned Cart Items
                                </h3>
                                <div
                                    class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">
                                            {{ $shoppingBehavior['abandoned_cart']['count'] }} item(s) in cart for >24
                                            hours
                                        </span>
                                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                            Rp
                                            {{ number_format($shoppingBehavior['abandoned_cart']['total_value'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @if ($shoppingBehavior['abandoned_cart']['items']->count() > 0)
                                        <ul class="mt-3 space-y-2">
                                            @foreach ($shoppingBehavior['abandoned_cart']['items'] as $item)
                                                @if ($item->product)
                                                    <li class="text-xs text-gray-600 dark:text-neutral-400">
                                                        • {{ $item->product->name }} ({{ $item->quantity }}x) -
                                                        Added {{ $item->created_at->diffForHumans() }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Most Purchased Categories -->
                        @if (isset($shoppingBehavior['most_purchased_categories']) && count($shoppingBehavior['most_purchased_categories']) > 0)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 mb-3">
                                    📊 Most Purchased Categories
                                </h3>
                                <div class="space-y-2">
                                    @foreach ($shoppingBehavior['most_purchased_categories'] as $category)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-neutral-700 rounded-lg">
                                            <div>
                                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                    {{ $category['name'] }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-neutral-500 ml-2">
                                                    {{ $category['quantity'] }} items
                                                </span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                                Rp {{ number_format($category['total_spent'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Most Purchased Products -->
                        @if (isset($shoppingBehavior['most_purchased_products']) && count($shoppingBehavior['most_purchased_products']) > 0)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 mb-3">
                                    ⭐ Most Purchased Products
                                </h3>
                                <div class="space-y-2">
                                    @foreach ($shoppingBehavior['most_purchased_products'] as $product)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-neutral-700 rounded-lg">
                                            <div>
                                                <a href="{{ route('products.show', $product['slug']) }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    {{ $product['name'] }}
                                                </a>
                                                <span class="text-xs text-gray-500 dark:text-neutral-500 ml-2">
                                                    {{ $product['quantity'] }} purchased
                                                </span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700 dark:text-neutral-300">
                                                Rp {{ number_format($product['total_spent'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Favorite Variants -->
                        @if (isset($shoppingBehavior['favorite_variants']) && count($shoppingBehavior['favorite_variants']) > 0)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 mb-3">
                                    💎 Favorite Product Variants
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($shoppingBehavior['favorite_variants'] as $variant)
                                        <span
                                            class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800/30 dark:text-indigo-400">
                                            {{ $variant['name'] }} ({{ $variant['quantity'] }}x)
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (
                            (!isset($shoppingBehavior['abandoned_cart']) || $shoppingBehavior['abandoned_cart']['count'] === 0) &&
                                (!isset($shoppingBehavior['most_purchased_categories']) ||
                                    count($shoppingBehavior['most_purchased_categories']) === 0) &&
                                (!isset($shoppingBehavior['most_purchased_products']) ||
                                    count($shoppingBehavior['most_purchased_products']) === 0) &&
                                (!isset($shoppingBehavior['favorite_variants']) || count($shoppingBehavior['favorite_variants']) === 0))
                            <div class="text-center text-sm text-gray-500 dark:text-neutral-500">
                                No shopping behavior data available yet.
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Admin Info -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700 p-6">
                    <div class="text-center">
                        <svg class="mx-auto size-16 text-gray-400 dark:text-neutral-600 mb-4"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-2">
                            Administrator Account
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                            This is an administrator account. Orders and addresses are only tracked for customer accounts.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
