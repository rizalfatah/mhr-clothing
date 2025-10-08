@extends('layouts.app')

@section('title', 'Home - Fashion & Style')

@section('content')
    <style>
        .full-screen {
            height: 100vh;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <div class="full-screen">
        <img src="{{ asset('images/banner.png') }}" alt="Banner Home" class="w-full h-full object-cover absolute inset-0">
        <button
            class="px-4 py-2 bg-gradient-to-r from-gray-400 to-gray-800 text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition transform hover:scale-105 duration-200 relative z-10">
            <a href="/shop" class="text-white text-xl">SHOP HERE</a>
        </button>
    </div>
@endsection
