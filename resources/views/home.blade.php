@extends('layouts.app')

@section('title', 'Fashion & Style')

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
        <a href="/catalog"
            class="group relative z-10 px-8 py-4 bg-white text-black text-xl font-bold rounded-full border-2 border-black shadow-2xl hover:shadow-black/50 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 overflow-hidden">
            <span class="relative z-10 tracking-wide">SHOP HERE</span>
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        </a>
    </div>
@endsection
