@extends('layouts.app')

@section('title', $product->name)

@push('styles')
    <style>
        .diagonal-line {
            position: relative;
            display: inline-block;
        }

        .diagonal-line::after {
            content: '';
            position: absolute;
            top: 50%;
            left: -10%;
            width: 125%;
            height: 3px;
            background-color: #ef4444;
            transform: translateY(-50%) rotate(-5deg);
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@section('content')
    <div class="bg-white min-h-screen">
        <div class="container max-w-5xl mx-auto px-4 py-4 md:py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                <!-- Product Image -->
                <div class="w-full">
                    @if ($product->images->count() > 0)
                        <!-- Main Image -->
                        <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-gray-100 mb-4">
                            <img id="mainImage"
                                src="{{ Storage::url($product->primaryImage->image_path ?? $product->images->first()->image_path) }}"
                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </div>

                        <!-- Image Thumbnails -->
                        @if ($product->images->count() > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($product->images as $image)
                                    <div class="relative aspect-[3/4] rounded-lg overflow-hidden bg-gray-100 cursor-pointer hover:opacity-75 transition"
                                        onclick="document.getElementById('mainImage').src='{{ Storage::url($image->image_path) }}'">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-gray-100">
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex flex-col">
                    <!-- Product Name -->
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

                    <!-- Category -->
                    @if ($product->category)
                        <div class="mb-3">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="mb-3">
                        @if ($product->original_price > $product->selling_price)
                            <span class="text-base text-gray-400 font-light diagonal-line mr-2">IDR
                                {{ number_format($product->original_price, 0, ',', '.') }}</span>
                            <div class="text-2xl font-bold text-gray-900 mt-1">IDR
                                {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                            <div class="mt-1 text-sm font-medium text-red-600">
                                Save
                                {{ number_format((($product->original_price - $product->selling_price) / $product->original_price) * 100, 0) }}%
                            </div>
                        @else
                            <div class="text-2xl font-bold text-gray-900">IDR
                                {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if ($product->description)
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <p class="text-sm text-gray-700">{{ $product->description }}</p>
                        </div>
                    @endif

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if ($product->is_featured)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium mb-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                Featured Product
                            </div>
                        @endif
                    </div>

                    <!-- Size Selector -->
                    @if ($product->variants->count() > 0)
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Select Size <span
                                    class="text-red-500">*</span></label>
                            <div id="size-selector" class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-2">
                                @foreach ($product->variants->sortBy('size') as $variant)
                                    <button type="button"
                                        class="size-option px-4 py-3 border-2 rounded-lg text-sm font-semibold transition
                                            {{ $variant->hasStock() ? 'border-gray-300 hover:border-gray-900 hover:bg-gray-50' : 'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                                        data-variant-id="{{ $variant->id }}" data-size="{{ $variant->size }}"
                                        data-stock="{{ $variant->stock }}"
                                        data-available="{{ $variant->hasStock() ? 'true' : 'false' }}"
                                        {{ !$variant->hasStock() ? 'disabled' : '' }}>
                                        <div>{{ $variant->size }}</div>
                                        <div
                                            class="text-xs {{ $variant->hasStock() ? 'text-gray-500' : 'text-gray-400' }}">
                                            {{ $variant->hasStock() ? $variant->stock . ' left' : 'Out of stock' }}
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                            <p id="size-error" class="text-sm text-red-600 mt-2 hidden">Please select a size</p>
                            <p id="stock-info" class="text-sm text-gray-600 mt-2 hidden"></p>
                        </div>
                    @endif

                    <!-- Quantity Selector -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Quantity</label>
                        <div class="flex items-center gap-3">
                            <button type="button" id="decrease-qty"
                                class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                </svg>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="99"
                                class="w-20 text-center border-2 border-gray-300 rounded-lg py-2 font-semibold focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <button type="button" id="increase-qty"
                                class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button id="add-to-cart-btn" data-product-id="{{ $product->id }}"
                        class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold text-base flex items-center justify-center gap-2 hover:bg-gray-800 transition mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Add to Cart</span>
                    </button>

                    <!-- Marketplace Links -->
                    @if ($product->tokopedia_url || $product->shopee_url || $product->tiktok_url)
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900 mb-3">Buy on Marketplace</h3>
                            <div class="space-y-2">
                                @if ($product->tokopedia_url)
                                    <a href="{{ $product->tokopedia_url }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-3 px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition group">
                                        <div class="flex-shrink-0 w-8 h-8  rounded-lg flex items-center justify-center">
                                            {{-- <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                            </svg> --}}

                                            <img src="{{ @asset('images/Tokopedia_Mascot.png') }}" alt=""
                                                srcset="">

                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-semibold text-gray-900">Tokopedia</span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif

                                @if ($product->shopee_url)
                                    <a href="{{ $product->shopee_url }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-3 px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition group">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center">
                                            {{-- <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M20 7h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 4h4v3h-4V4zm10 16H4V9h16v11z" />
                                            </svg> --}}

                                            <?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 109.59 122.88"
                                                style="enable-background:new 0 0 109.59 122.88" xml:space="preserve">
                                                <style type="text/css">
                                                    <![CDATA[
                                                    .st0 {
                                                        fill: #EE4D2D;
                                                    }
                                                    ]]>
                                                </style>
                                                <g>
                                                    <path class="st0"
                                                        d="M74.98,91.98C76.15,82.36,69.96,76.22,53.6,71c-7.92-2.7-11.66-6.24-11.57-11.12 c0.33-5.4,5.36-9.34,12.04-9.47c4.63,0.09,9.77,1.22,14.76,4.56c0.59,0.37,1.01,0.32,1.35-0.2c0.46-0.74,1.61-2.53,2-3.17 c0.26-0.42,0.31-0.96-0.35-1.44c-0.95-0.7-3.6-2.13-5.03-2.72c-3.88-1.62-8.23-2.64-12.86-2.63c-9.77,0.04-17.47,6.22-18.12,14.47 c-0.42,5.95,2.53,10.79,8.86,14.47c1.34,0.78,8.6,3.67,11.49,4.57c9.08,2.83,13.8,7.9,12.69,13.81c-1.01,5.36-6.65,8.83-14.43,8.93 c-6.17-0.24-11.71-2.75-16.02-6.1c-0.11-0.08-0.65-0.5-0.72-0.56c-0.53-0.42-1.11-0.39-1.47,0.15c-0.26,0.4-1.92,2.8-2.34,3.43 c-0.39,0.55-0.18,0.86,0.23,1.2c1.8,1.5,4.18,3.14,5.81,3.97c4.47,2.28,9.32,3.53,14.48,3.72c3.32,0.22,7.5-0.49,10.63-1.81 C70.63,102.67,74.25,97.92,74.98,91.98L74.98,91.98z M54.79,7.18c-10.59,0-19.22,9.98-19.62,22.47h39.25 C74.01,17.16,65.38,7.18,54.79,7.18L54.79,7.18z M94.99,122.88l-0.41,0l-80.82-0.01h0c-5.5-0.21-9.54-4.66-10.09-10.19l-0.05-1 l-3.61-79.5v0C0,32.12,0,32.06,0,32c0-1.28,1.03-2.33,2.3-2.35l0,0h25.48C28.41,13.15,40.26,0,54.79,0s26.39,13.15,27.01,29.65 h25.4h0.04c1.3,0,2.35,1.05,2.35,2.35c0,0.04,0,0.08,0,0.12v0l-3.96,79.81l-0.04,0.68C105.12,118.21,100.59,122.73,94.99,122.88 L94.99,122.88z" />
                                                </g>
                                            </svg>

                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-semibold text-gray-900">Shopee</span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif

                                @if ($product->tiktok_url)
                                    <a href="{{ $product->tiktok_url }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-3 px-4 py-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-semibold text-gray-900">TikTok Shop</span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-slate-900 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Product Details -->
                    @if ($product->details)
                        <div class="mb-4">
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Product Details:</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-line">
                                {{ $product->details }}
                            </div>
                        </div>
                    @endif

                    <!-- Delivery Info -->
                    <div class="bg-gray-100 rounded-xl p-4 mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Shipping</h3>
                        <div class="space-y-1.5 text-sm text-gray-700">
                            <div class="flex justify-between">
                                <span>Weight:</span>
                                <span class="font-medium">{{ $product->weight }}g</span>
                            </div>
                            <div class="text-xs text-gray-600 mt-2">
                                Shipped within 48 hours
                                <div>(After payment confirmation)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Buy Now Button -->
                    <button id="buy-now-btn" data-product-id="{{ $product->id }}"
                        class="w-full bg-white border-2 border-gray-900 text-gray-900 py-3 rounded-lg font-semibold text-base hover:bg-gray-50 transition">
                        Buy Now
                    </button>
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <x-related-product-card :name="$relatedProduct->name" :price="number_format($relatedProduct->selling_price, 0, ',', '.')" :oldPrice="number_format($relatedProduct->original_price, 0, ',', '.')" :image="$relatedProduct->primaryImage
                                ? 'storage/' . $relatedProduct->primaryImage->image_path
                                : 'images/products/product-1.png'"
                                :link="route('products.show', $relatedProduct->slug)" :id="$relatedProduct->id" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Size selection
        let selectedVariantId = null;
        let selectedMaxStock = null;
        const sizeButtons = document.querySelectorAll('.size-option');
        const sizeError = document.getElementById('size-error');
        const stockInfo = document.getElementById('stock-info');

        sizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.dataset.available === 'false') return;

                // Remove selected state from all buttons
                sizeButtons.forEach(btn => {
                    btn.classList.remove('border-gray-900', 'bg-gray-900', 'text-white',
                        'hover:text-gray-900');
                    btn.classList.add('border-gray-300');
                });

                // Add selected state to clicked button
                this.classList.remove('border-gray-300');
                this.classList.add('border-gray-900', 'bg-gray-900', 'text-white', 'hover:text-gray-900');

                // Store selected variant
                selectedVariantId = parseInt(this.dataset.variantId);
                selectedMaxStock = parseInt(this.dataset.stock);

                // Hide error and show stock info
                sizeError.classList.add('hidden');
                stockInfo.classList.remove('hidden');
                stockInfo.textContent = `${selectedMaxStock} pcs available for size ${this.dataset.size}`;

                // Update quantity max
                quantityInput.max = selectedMaxStock;
                if (parseInt(quantityInput.value) > selectedMaxStock) {
                    quantityInput.value = selectedMaxStock;
                }
            });
        });

        // Quantity controls
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const buyNowBtn = document.getElementById('buy-now-btn');

        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            const maxValue = selectedMaxStock || 99;
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });

        // Prevent manual input outside range
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            const maxValue = selectedMaxStock || 99;
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > maxValue) {
                this.value = maxValue;
            }
        });

        // Add to cart functionality
        addToCartBtn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(quantityInput.value);

            // Validate size selection
            @if ($product->variants->count() > 0)
                if (!selectedVariantId) {
                    sizeError.classList.remove('hidden');
                    sizeError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }
            @endif

            // Disable button and show loading state
            const originalContent = this.innerHTML;
            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Adding...</span>
            `;

            try {
                const requestBody = {
                    product_id: productId,
                    quantity: quantity
                };

                @if ($product->variants->count() > 0)
                    requestBody.product_variant_id = selectedVariantId;
                @endif

                const response = await fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(requestBody)
                });

                const data = await response.json();

                if (data.success) {
                    // Show success state
                    this.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Successfully Added!</span>
                    `;

                    // Update cart counter
                    if (typeof window.updateCartCounter === 'function') {
                        window.updateCartCounter();
                    }

                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }, 2000);

                    // Show cart notification or badge update if you have one
                    console.log('Product added to cart:', data);
                } else {
                    // Show error message from backend
                    alert(data.message || 'Failed to add to cart');
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                alert('An error occurred while adding product to cart. Please try again.');
                this.innerHTML = originalContent;
                this.disabled = false;
            }
        });

        // Buy now functionality
        buyNowBtn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(quantityInput.value);

            // Validate size selection
            @if ($product->variants->count() > 0)
                if (!selectedVariantId) {
                    sizeError.classList.remove('hidden');
                    sizeError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }
            @endif

            // Disable button and show loading state
            const originalContent = this.innerHTML;
            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-gray-900 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            try {
                const requestBody = {
                    product_id: productId,
                    quantity: quantity
                };

                @if ($product->variants->count() > 0)
                    requestBody.product_variant_id = selectedVariantId;
                @endif

                const response = await fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(requestBody)
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to checkout
                    window.location.href = '{{ route('checkout.index') }}';
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                this.innerHTML = originalContent;
                this.disabled = false;
            }
        });
    </script>
@endpush
