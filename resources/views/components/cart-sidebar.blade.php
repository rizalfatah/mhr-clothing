<!-- Cart Sidebar Backdrop -->
<div id="cart-backdrop" class="fixed inset-0 bg-black/50 z-[60] hidden transition-opacity"></div>

<!-- Cart Sidebar -->
<div id="cart-sidebar"
    class="fixed top-0 right-0 h-full w-full md:w-96 bg-white shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 flex flex-col">
    <!-- Cart Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold">Shopping Cart</h2>
        <button id="cart-close" class="text-gray-500 hover:text-gray-900 transition" aria-label="Close cart">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Cart Items -->
    <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
        <!-- Cart items will be loaded here dynamically -->
        <div id="cart-loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-300 border-t-gray-900">
            </div>
            <p class="text-gray-500 mt-2">Loading cart...</p>
        </div>
        <div id="cart-empty" class="text-center py-8 hidden">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p class="text-gray-500 font-medium">Your cart is empty</p>
            <button onclick="closeCart()"
                class="mt-4 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
                aria-label="Start shopping">
                Start Shopping
            </button>
        </div>
        <div id="cart-items" class="space-y-4">
            <!-- Cart items will be inserted here -->
        </div>
    </div>

    <!-- Cart Footer -->
    <div id="cart-footer" class="border-t border-gray-200 p-6 space-y-4 hidden">
        <!-- Subtotal -->
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-semibold" id="cart-subtotal">Rp 0</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Ongkir</span>
            <span class="font-semibold" id="cart-shipping">Rp 0</span>
        </div>
        <div class="border-t pt-4 flex justify-between items-center">
            <span class="text-lg font-bold">Total</span>
            <span class="text-lg font-bold" id="cart-total">Rp 0</span>
        </div>

        <!-- Checkout Button -->
        <a href="{{ route('checkout.index') }}"
            class="block w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition text-center">
            Lanjut ke Checkout
        </a>
        <button id="continue-shopping"
            class="w-full border border-gray-300 text-gray-900 py-3 rounded-lg font-semibold hover:bg-gray-50 transition"
            aria-label="Continue shopping">
            Lanjut Belanja
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
            loadCart();
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

        // Load cart data
        async function loadCart() {
            const cartLoading = document.getElementById('cart-loading');
            const cartEmpty = document.getElementById('cart-empty');
            const cartItems = document.getElementById('cart-items');
            const cartFooter = document.getElementById('cart-footer');

            // Show loading
            cartLoading.classList.remove('hidden');
            cartEmpty.classList.add('hidden');
            cartItems.classList.add('hidden');
            cartFooter.classList.add('hidden');

            try {
                const response = await fetch('{{ route('cart.get') }}');
                const data = await response.json();

                cartLoading.classList.add('hidden');

                if (data.items.length === 0) {
                    cartEmpty.classList.remove('hidden');
                } else {
                    cartItems.innerHTML = '';
                    data.items.forEach(item => {
                        cartItems.innerHTML += renderCartItem(item);
                    });
                    cartItems.classList.remove('hidden');
                    cartFooter.classList.remove('hidden');

                    // Update totals
                    document.getElementById('cart-subtotal').textContent = formatPrice(data.subtotal);
                    document.getElementById('cart-shipping').textContent = data.shipping_cost === 0 ? 'GRATIS' :
                        formatPrice(data.shipping_cost);
                    document.getElementById('cart-total').textContent = formatPrice(data.total);
                }
            } catch (error) {
                console.error('Error loading cart:', error);
                cartLoading.classList.add('hidden');
                cartEmpty.classList.remove('hidden');
            }
        }

        // Render cart item HTML
        function renderCartItem(item) {
            const imageUrl = item.image ? `{{ asset('storage') }}/${item.image}` :
                'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="80" height="80"%3E%3Crect fill="%23e5e7eb" width="80" height="80"/%3E%3C/svg%3E';

            return `
                <div class="flex gap-4 pb-4 border-b border-gray-200">
                    <img src="${imageUrl}" alt="${item.name}" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-semibold text-sm mb-1">${item.name}</h3>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-2">
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})"
                                    class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100" aria-label="Decrease quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                    </svg>
                                </button>
                                <span class="text-sm font-medium">${item.quantity}</span>
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})"
                                    class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100" aria-label="Increase quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                                    </svg>
                                </button>
                            </div>
                            <span class="font-bold text-sm">${formatPrice(item.subtotal)}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">${formatPrice(item.price)} x ${item.quantity}</p>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500 transition" aria-label="Remove item from cart">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
        }

        // Update cart item quantity
        async function updateCartQuantity(productId, quantity) {
            try {
                const response = await fetch('{{ route('cart.update') }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                    // Update cart counter
                    if (typeof window.updateCartCounter === 'function') {
                        window.updateCartCounter();
                    }
                }
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        }

        // Remove item from cart
        async function removeFromCart(productId) {
            if (!confirm('Remove product from cart?')) return;

            try {
                const response = await fetch('{{ route('cart.remove') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                    // Update cart counter
                    if (typeof window.updateCartCounter === 'function') {
                        window.updateCartCounter();
                    }
                }
            } catch (error) {
                console.error('Error removing from cart:', error);
            }
        }

        // Format price to Rupiah
        function formatPrice(price) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
        }
    </script>
@endpush
