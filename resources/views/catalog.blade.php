@extends('layouts.app')

@section('title', 'Catalog Products')

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
    <div class="full-screen bg-white">
        <!-- Catalog Header -->
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Our Catalog</h1>
                <p class="text-gray-600">Discover our latest collection</p>
            </div>

            <!-- Filter & Sort -->
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <!-- Category Filter -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('catalog') }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                        All
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('catalog', ['category' => $category->id]) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('category') == $category->id ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Sort -->
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm font-medium text-gray-900">Sort:</label>
                    <select id="sort" name="sort"
                        onchange="window.location.href='{{ route('catalog') }}?category={{ request('category') }}&sort=' + this.value"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-gray-900">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price
                        </option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price
                        </option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach ($products as $product)
                        <x-product-card :name="$product->name" :price="number_format($product->selling_price, 0, ',', '.')" :oldPrice="number_format($product->original_price, 0, ',', '.')" :image="$product->primaryImage
                            ? 'storage/' . $product->primaryImage->image_path
                            : 'images/products/product-1.png'"
                            :link="route('products.show', $product->slug)" :id="$product->id" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $products->links('vendor.pagination.simple-tailwind') }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada produk</h3>
                    <p class="text-gray-600">Produk tidak ditemukan untuk filter yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
