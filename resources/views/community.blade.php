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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-fr">
                <!-- Image 1 -->
                <div
                    class="sm:row-span-2 group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 min-h-[300px] lg:min-h-0">
                    <img src="{{ asset('images/community/1.png') }}" alt="Community Member"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <p class="font-semibold">Style Inspiration</p>
                        </div>
                    </div>
                </div>

                <!-- Image 2 -->
                <div
                    class="sm:col-span-2 sm:row-span-2 group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 min-h-[300px] lg:min-h-0">
                    <img src="{{ asset('images/community/2.png') }}" alt="Community Member"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <p class="font-semibold">Fashion Forward</p>
                        </div>
                    </div>
                </div>

                <!-- Image 3 -->
                <div
                    class="sm:row-span-3 lg:col-start-4 group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 min-h-[400px] lg:min-h-0">
                    <img src="{{ asset('images/community/3.png') }}" alt="Community Member"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <p class="font-semibold">Street Style</p>
                        </div>
                    </div>
                </div>

                <!-- Image 4 -->
                <div
                    class="sm:col-span-2 sm:row-span-2 lg:col-span-3 lg:row-start-3 group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 min-h-[300px] lg:min-h-0">
                    <img src="{{ asset('images/community/4.png') }}" alt="Community Member"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <p class="font-semibold">Urban Vibes</p>
                        </div>
                    </div>
                </div>
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
