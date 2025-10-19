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
                <button class="px-6 py-2 bg-white text-gray-900 font-medium rounded hover:bg-gray-100 transition">
                    Login
                </button>
                <button class="px-6 py-2 bg-black text-white font-medium rounded hover:bg-gray-800 transition">
                    Signup
                </button>
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
                    <p class="text-gray-600 text-center py-12">No orders yet</p>
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
