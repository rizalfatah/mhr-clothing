@extends('layouts.app')

@section('title', 'Catalog')

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

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-10">
                @for ($i = 1; $i <= 3; $i++)
                    <x-product-card name="MHR Threshold" price="385.000" oldPrice="500.000"
                        image="images/products/product-1.png" link="/products/{{ $i }}" :id="$i" />
                @endfor
            </div>

            <!-- Load More Button -->
            {{-- <div class="text-center mt-12">
                <button
                    class="px-8 py-3 bg-gray-900 text-white rounded-full hover:bg-gray-800 transition-colors duration-300 font-medium">
                    Load More
                </button>
            </div> --}}
        </div>
    </div>
@endsection
