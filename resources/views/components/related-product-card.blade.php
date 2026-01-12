@props([
    'name' => 'Product Name',
    'price' => '385.000',
    'oldPrice' => '500.000',
    'image' => 'images/products/product-1.png',
    'link' => '#',
    'id' => 1,
])

<div
    class="related-product-card bg-white rounded-2xl md:rounded-3xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <!-- Product Image -->
    <div class="relative aspect-[3/4] overflow-hidden">
        <img src="{{ asset($image) }}" alt="{{ $name }}" class="w-full h-full object-cover">

        <!-- Product Name Floating -->
        <a href="{{ $link }}" class="absolute bottom-24 md:bottom-28 left-2 right-2 text-center block">
            <h3
                class="font-bold text-white text-sm md:text-base lg:text-lg drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)] hover:scale-105 transition-transform duration-300 leading-tight px-1 line-clamp-2">
                {{ $name }}</h3>
        </a>

        <!-- Product Info Floating -->
        <a href="{{ $link }}"
            class="absolute bottom-2 md:bottom-3 left-2 right-2 bg-white rounded-xl md:rounded-2xl p-3 md:p-4 text-center shadow-xl hover:shadow-2xl transition-all duration-300 block">
            <div class="flex flex-col items-center gap-0.5 md:gap-1">
                <span class="text-xs md:text-sm text-gray-400 font-light diagonal-line">IDR {{ $oldPrice }}</span>
                <span
                    class="text-base md:text-lg lg:text-xl font-semibold text-gray-900 tracking-tight leading-tight">IDR
                    {{ $price }}</span>
            </div>
        </a>
    </div>
</div>
