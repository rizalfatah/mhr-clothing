<!-- Cart Sidebar Backdrop -->
<div id="cart-backdrop" class="fixed inset-0 bg-black/50 z-[60] hidden transition-opacity"></div>

<!-- Cart Sidebar -->
<div id="cart-sidebar"
    class="fixed top-0 right-0 h-full w-full md:w-96 bg-white shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 flex flex-col">
    <!-- Cart Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold">Shopping Cart</h2>
        <button id="cart-close" class="text-gray-500 hover:text-gray-900 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <!-- Sample Cart Item 1 -->
        <div class="flex gap-4 pb-4 border-b border-gray-200">
            <img src="{{ asset('images/products/product-1.png') }}" alt="Product"
                class="w-20 h-20 object-cover rounded">
            <div class="flex-1">
                <h3 class="font-semibold text-sm mb-1">Classic T-Shirt</h3>
                <p class="text-xs text-gray-600 mb-2">Size: M | Color: Black</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button
                            class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                            </svg>
                        </button>
                        <span class="text-sm font-medium">1</span>
                        <button
                            class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                            </svg>
                        </button>
                    </div>
                    <span class="font-bold text-sm">$29.99</span>
                </div>
            </div>
            <button class="text-gray-400 hover:text-red-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sample Cart Item 2 -->
        <div class="flex gap-4 pb-4 border-b border-gray-200">
            <img src="{{ asset('images/products/detail-1.png') }}" alt="Product"
                class="w-20 h-20 object-cover rounded">
            <div class="flex-1">
                <h3 class="font-semibold text-sm mb-1">Denim Jacket</h3>
                <p class="text-xs text-gray-600 mb-2">Size: L | Color: Blue</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button
                            class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                            </svg>
                        </button>
                        <span class="text-sm font-medium">1</span>
                        <button
                            class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                            </svg>
                        </button>
                    </div>
                    <span class="font-bold text-sm">$79.99</span>
                </div>
            </div>
            <button class="text-gray-400 hover:text-red-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>

    <!-- Cart Footer -->
    <div class="border-t border-gray-200 p-6 space-y-4">
        <!-- Subtotal -->
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-semibold">$199.97</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Shipping</span>
            <span class="font-semibold">Free</span>
        </div>
        <div class="border-t pt-4 flex justify-between items-center">
            <span class="text-lg font-bold">Total</span>
            <span class="text-lg font-bold">$199.97</span>
        </div>

        <!-- Checkout Button -->
        <button class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
            Proceed to Checkout
        </button>
        <button id="continue-shopping"
            class="w-full border border-gray-300 text-gray-900 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
            Continue Shopping
        </button>
    </div>
</div>

@push('scripts')
    <script>
        // Cart sidebar functionality
        const cartToggle = document.getElementById('cart-toggle');
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartBackdrop = document.getElementById('cart-backdrop');
        const cartClose = document.getElementById('cart-close');

        // Open cart
        cartToggle?.addEventListener('click', function() {
            cartSidebar?.classList.remove('translate-x-full');
            cartBackdrop?.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        });

        // Close cart
        function closeCart() {
            cartSidebar?.classList.add('translate-x-full');
            cartBackdrop?.classList.add('hidden');
            document.body.style.overflow = ''; // Restore body scroll
        }

        cartClose?.addEventListener('click', closeCart);
        cartBackdrop?.addEventListener('click', closeCart);

        // Continue shopping button
        const continueShopping = document.getElementById('continue-shopping');
        continueShopping?.addEventListener('click', closeCart);

        // Close cart with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !cartSidebar?.classList.contains('translate-x-full')) {
                closeCart();
            }
        });
    </script>
@endpush
