@extends('layouts.app')

@section('title', 'Product Detail')

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
                    <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ asset('images/products/detail-1.png') }}" alt="MHR Threshold"
                            class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col">
                    <!-- Product Name -->
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">MHR Threshold</h1>

                    <!-- Price -->
                    <div class="mb-3">
                        <span class="text-base text-gray-400 font-light diagonal-line mr-2">IDR 450.000</span>
                        <div class="text-2xl font-bold text-gray-900 mt-1">IDR 385.000</div>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 fill-current text-gray-900" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-lg font-semibold text-gray-900">4.7</span>
                    </div>

                    <!-- Size Selection & Quantity -->
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Size</h3>
                        <div class="flex items-center justify-between gap-4">
                            <!-- Size Buttons -->
                            <div class="flex gap-2">
                                <button
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-900 hover:border-gray-900 transition">
                                    S
                                </button>
                                <button
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-900 hover:border-gray-900 transition">
                                    M
                                </button>
                                <button
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-900 hover:border-gray-900 transition">
                                    L
                                </button>
                                <button
                                    class="px-4 py-2 bg-gray-900 text-white border border-gray-900 rounded-md text-sm font-medium">
                                    XL
                                </button>
                            </div>

                            <!-- Quantity -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-8 h-8 rounded-full border border-gray-900 flex items-center justify-center text-lg font-bold hover:bg-gray-900 hover:text-white transition">
                                    +
                                </button>
                                <span class="text-xl font-bold text-gray-900 min-w-[40px] text-center">3</span>
                                <button
                                    class="w-8 h-8 rounded-full border border-gray-900 flex items-center justify-center text-lg font-bold hover:bg-gray-900 hover:text-white transition">
                                    −
                                </button>
                            </div>
                        </div>
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

                    <!-- Product Details -->
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Product Details :</h3>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li class="flex items-start">
                                <span class="mr-2">›</span>
                                <span>Catton Combed 16s</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">›</span>
                                <span>Jerse</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">›</span>
                                <span>Daedora</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Delivery Info -->
                    <div class="bg-gray-100 rounded-xl p-4 mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Delivery</h3>
                        <div class="space-y-1.5 text-sm text-gray-700">
                            <div class="flex justify-between">
                                <span>Delivery to :</span>
                                <span class="font-medium">Pick Area V</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Weight :</span>
                                <span class="font-medium">900gm</span>
                            </div>
                            <div class="text-xs text-gray-600 mt-2">
                                Shipped within 48 hours
                                <div>(Upon confirmation of payment)</div>
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
        </div>
    </div>
@endsection
