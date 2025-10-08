@props([
    'name' => 'Product Name',
    'price' => '385.000',
    'oldPrice' => '500.000',
    'image' => 'images/products/product-1.png',
    'link' => '#',
    'id' => 1,
])

<div class="product-card bg-white rounded-3xl shadow-lg overflow-hidden card-hover">
    <!-- Product Image -->
    <div class="relative aspect-[3/4] overflow-hidden">
        <img src="{{ asset($image) }}" alt="{{ $name }}" class="w-full h-full object-cover">

        <!-- Product Name Floating -->
        <a href="{{ $link }}" class="absolute bottom-30 left-4 right-4 text-center block">
            <h3 class="font-bold text-white text-lg drop-shadow-lg hover:scale-105 transition-transform">
                {{ $name }}</h3>
        </a>

        <!-- Product Info Floating -->
        <a href="{{ $link }}"
            class="absolute bottom-4 left-4 right-4 bg-white rounded-2xl p-4 text-center shadow-xl hover:shadow-2xl transition-shadow block">
            <div class="flex flex-col items-center gap-1">
                <span class="text-md text-gray-400 font-light diagonal-line">IDR {{ $oldPrice }}</span>
                <span class="text-3xl font-normal text-gray-900">IDR {{ $price }}</span>
            </div>
        </a>
    </div>
</div>
