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
                                Hemat
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
                                Produk Unggulan
                            </div>
                        @endif
                    </div>

                    <!-- Notify Me Button -->
                    <button
                        class="w-full bg-gray-900 text-white py-2.5 rounded-lg font-semibold text-base flex items-center justify-center gap-2 hover:bg-gray-800 transition mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        Notify me
                    </button>

                    <!-- Marketplace Links -->
                    @if ($product->tokopedia_url || $product->shopee_url || $product->tiktok_url)
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900 mb-3">Beli di Marketplace</h3>
                            <div class="space-y-2">
                                @if ($product->tokopedia_url)
                                    <a href="{{ $product->tokopedia_url }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-3 px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition group">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                            {{-- <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                            </svg> --}}

                                            <img src="https://brandfetch.com/tokopedia.com?view=library&library=default&collection=logos&asset=id9X_tkaHj&utm_source=https%253A%252F%252Fbrandfetch.com%252Ftokopedia.com&utm_medium=copyAction&utm_campaign=brandPageReferral"
                                                alt="Tokopedia Logo">
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
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M20 7h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 4h4v3h-4V4zm10 16H4V9h16v11z" />
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
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Detail Produk:</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-line">
                                {{ $product->details }}
                            </div>
                        </div>
                    @endif

                    <!-- Delivery Info -->
                    <div class="bg-gray-100 rounded-xl p-4 mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Pengiriman</h3>
                        <div class="space-y-1.5 text-sm text-gray-700">
                            <div class="flex justify-between">
                                <span>Berat:</span>
                                <span class="font-medium">{{ $product->weight }}g</span>
                            </div>
                            <div class="text-xs text-gray-600 mt-2">
                                Dikirim dalam 48 jam
                                <div>(Setelah konfirmasi pembayaran)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Notify Me Button -->
                    <button
                        class="w-full bg-gray-900 text-white py-2.5 rounded-lg font-semibold text-base hover:bg-gray-800 transition">
                        Notify me
                    </button>
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <x-product-card :name="$relatedProduct->name" :price="number_format($relatedProduct->selling_price, 0, ',', '.')" :oldPrice="number_format($relatedProduct->original_price, 0, ',', '.')" :image="$relatedProduct->primaryImage
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
