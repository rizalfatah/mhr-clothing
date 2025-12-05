@extends('layouts.app')

@section('title', 'Account')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Page Title -->
        <h1 class="text-4xl font-bold mb-8">My Account</h1>

        <!-- Login/Signup Banner -->
        <div class="bg-gray-300 rounded-lg p-6 mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold mb-2">Enjoy Special Discounts and Stay Connected</h2>
                <p class="text-gray-700 text-sm">
                    Get access to exclusive discounts while keeping track of your orders and chats with ease.<br>
                    Stay updated on your purchases and engage with us seamlessly, all in one place.
                </p>
            </div>
            <div class="flex gap-3 ml-6">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2 bg-white text-gray-900 font-medium rounded hover:bg-gray-100 transition">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="px-6 py-2 bg-white text-gray-900 font-medium rounded hover:bg-gray-100 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-2 bg-black text-white font-medium rounded hover:bg-gray-800 transition">
                        Signup
                    </a>
                @endguest
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="border-2 border-black-400 rounded-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex bg-gray-300">
                <button class="flex-1 py-3 px-6 font-semibold border-b-4 border-black bg-gray-300 transition"
                    id="orders-tab">
                    Orders
                </button>
                <button class="flex-1 py-3 px-6 font-semibold text-gray-500 hover:text-gray-900 transition"
                    id="wishlist-tab">
                    Wishlist
                </button>
            </div>

            <!-- Tab Content -->
            <div class="bg-gray-300 p-6 min-h-[300px]" id="tab-content">
                <!-- Orders Content -->
                <div id="orders-content">
                    @if ($orders->count() > 0)
                        <div class="grid gap-4">
                            @foreach ($orders as $order)
                                <div class="bg-white rounded-lg border-2 border-gray-400 p-6 hover:shadow-md transition">
                                    <!-- Order Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg mb-1">{{ $order->order_number }}</h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $order->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                            {{ $order->status_label }}
                                        </span>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="border-t border-gray-200 pt-4 mb-4">
                                        <h4 class="font-semibold mb-2 text-sm text-gray-700">Order Items:</h4>
                                        <div class="space-y-2">
                                            @foreach ($order->items as $item)
                                                <div class="flex justify-between items-center text-sm">
                                                    <div class="flex-1">
                                                        <p class="font-medium">{{ $item->product_name }}</p>
                                                        <p class="text-gray-600">Qty: {{ $item->quantity }} × Rp
                                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                                    </div>
                                                    <p class="font-semibold">Rp
                                                        {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="flex justify-between items-center mb-2 text-sm">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-2 text-sm">
                                            <span class="text-gray-600">Shipping Cost</span>
                                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center font-bold text-lg border-t pt-2">
                                            <span>Total</span>
                                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Shipping Info -->
                                    @if ($order->tracking_number)
                                        <div class="mt-4 p-3 bg-blue-50 rounded border border-blue-200">
                                            <p class="text-sm font-semibold text-blue-900">Tracking Information</p>
                                            <p class="text-sm text-blue-800">{{ $order->courier }}:
                                                {{ $order->tracking_number }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-12">No orders yet</p>
                    @endif
                </div>

                <!-- Wishlist Content (Hidden by default) -->
                <div id="wishlist-content" class="hidden">
                    <p class="text-gray-600 text-center py-12">Your wishlist is empty</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const ordersTab = document.getElementById('orders-tab');
            const wishlistTab = document.getElementById('wishlist-tab');
            const ordersContent = document.getElementById('orders-content');
            const wishlistContent = document.getElementById('wishlist-content');

            ordersTab.addEventListener('click', function() {
                // Update tab styles
                ordersTab.classList.add('border-b-4', 'border-black', 'bg-gray-300');
                ordersTab.classList.remove('text-gray-500');
                wishlistTab.classList.remove('border-b-4', 'border-black', 'bg-gray-300');
                wishlistTab.classList.add('text-gray-500');

                // Show/hide content
                ordersContent.classList.remove('hidden');
                wishlistContent.classList.add('hidden');
            });

            wishlistTab.addEventListener('click', function() {
                // Update tab styles
                wishlistTab.classList.add('border-b-4', 'border-black', 'bg-gray-300');
                wishlistTab.classList.remove('text-gray-500');
                ordersTab.classList.remove('border-b-4', 'border-black', 'bg-gray-300');
                ordersTab.classList.add('text-gray-500');

                // Show/hide content
                wishlistContent.classList.remove('hidden');
                ordersContent.classList.add('hidden');
            });
        </script>
    @endpush
@endsection
