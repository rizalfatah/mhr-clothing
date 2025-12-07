<header class="sticky top-0 z-50 bg-linear-to-r from-white to-gray-300 shadow-sm">
    <!-- Top Bar -->
    <div class="bg-gray-900 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ \App\Models\Setting::get('whatsapp_admin_number', '') }}
                    </span>
                    <span class="hidden md:flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ \App\Models\Setting::get('contact_email', '') }}
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="hidden md:inline">{{ \App\Models\Setting::get('promotional_banner', '') }}</span>
                    <div class="flex gap-3">
                        <a href="#" class="hover:text-gray-300 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-gray-300 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-gray-300 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex flex-col items-center gap-1 text-gray-900">
                <img src="{{ asset('logo-mhr.svg') }}" alt="MHR Clothing Logo" class="w-12 h-12">
                <span class="text-l font-bold">MHR</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="/catalog"
                    class="nav-link font-medium transition px-4 py-2 rounded-lg {{ request()->is('catalog') ? 'bg-gray-50 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Catalog</a>
                <a href="/community"
                    class="nav-link font-medium transition px-4 py-2 rounded-lg {{ request()->is('community') ? 'bg-gray-50 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Community</a>
                <a href="/about"
                    class="nav-link font-medium transition px-4 py-2 rounded-lg {{ request()->is('about') ? 'bg-gray-50 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">About</a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-4">

                <!-- Cart -->
                <button id="cart-toggle"
                    class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $cartCount = $cartService->getCount();
                    @endphp
                    <span id="cart-counter-badge"
                        class="absolute -top-2 -right-2 bg-black text-white text-xs w-5 h-5 rounded-full flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
                </button>

                <!-- Account -->
                <a href="/account"
                    class="hidden md:flex items-center gap-2 text-gray-700 hover:text-gray-900 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </a>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="lg:hidden text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden hidden border-t border-gray-200 bg-white">
        <div class="container mx-auto px-4 py-4 space-y-3">
            <a href="/catalog"
                class="block font-medium transition px-4 py-2 rounded-lg {{ request()->is('catalog') ? 'bg-gray-50 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Catalog</a>
            <a href="/community"
                class="block font-medium transition px-4 py-2 rounded-lg {{ request()->is('community') ? 'bg-gray-50 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Community</a>
            <a href="/about"
                class="block font-medium transition px-4 py-2 rounded-lg {{ request()->is('about') ? 'bg-gray-50 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">About</a>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu?.classList.toggle('hidden');
        });

        // Global function to update cart counter
        window.updateCartCounter = async function() {
            try {
                const response = await fetch('{{ route('cart.get') }}');
                const data = await response.json();

                const badge = document.getElementById('cart-counter-badge');
                if (badge) {
                    badge.textContent = data.count;
                    if (data.count > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error updating cart counter:', error);
            }
        };
    </script>
@endpush
