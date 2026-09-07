@extends('layouts.app')

@section('title', 'Community')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Our Community</h1>
                <p class="text-gray-600">Join our vibrant community and share your style</p>
            </div>

            <!-- Masonry Gallery Grid -->
            <div data-masonry-grid
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 [grid-auto-rows:8px] [grid-auto-flow:dense]">
                @foreach ($communityImages as $image)
                    <article data-masonry-item>
                        <figure data-masonry-content
                            class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300">
                            <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" width="{{ $image['width'] }}"
                                height="{{ $image['height'] }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                fetchpriority="{{ $loop->first ? 'high' : 'auto' }}" decoding="async"
                                class="block w-full h-auto group-hover:scale-110 transition-transform duration-300">

                            @if (!empty($image['caption']))
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <figcaption data-masonry-caption class="absolute bottom-4 left-4 text-white font-semibold">
                                        {{ $image['caption'] }}
                                    </figcaption>
                                </div>
                            @endif
                        </figure>
                    </article>
                @endforeach
            </div>

            <!-- Call to Action -->
            {{-- <div class="mt-16 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Be Part of Our Story</h2>
                <p class="text-gray-600 mb-8 max-w-xl mx-auto">Share your MHR Clothing moments and get featured in our
                    community gallery</p>
                <button
                    class="bg-black text-white px-8 py-3 rounded-full font-semibold hover:bg-gray-800 transition-colors duration-300 shadow-lg hover:shadow-xl">
                    Share Your Style
                </button>
            </div> --}}
        </div>
    </div>
@endsection
