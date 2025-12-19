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
