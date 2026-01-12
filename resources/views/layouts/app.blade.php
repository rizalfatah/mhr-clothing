<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Fashion & Style') - MHR</title>
    <meta name="description" content="@yield('description', 'Discover the latest fashion trends and stylish clothing at MHR. Shop premium quality apparel, accessories, and exclusive collections for men and women.')">
    <meta name="keywords" content="@yield('keywords', 'fashion, clothing, style, apparel, MHR, men fashion, women fashion, trendy clothes, online shopping')">
    <meta name="author" content="MHR Clothing">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <meta name="googlebot" content="@yield('googlebot', 'index, follow')">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="MHR Clothing">
    <meta property="og:title" content="@yield('og_title', 'Fashion & Style - MHR Clothing')">
    <meta property="og:description" content="@yield('og_description', 'Discover the latest fashion trends and stylish clothing at MHR. Shop premium quality apparel, accessories, and exclusive collections for men and women.')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'MHR Clothing - Fashion & Style')">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', 'Fashion & Style - MHR Clothing')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Discover the latest fashion trends and stylish clothing at MHR. Shop premium quality apparel, accessories, and exclusive collections.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt', 'MHR Clothing - Fashion & Style')">
    @hasSection('twitter_site')
        <meta name="twitter:site" content="@yield('twitter_site')">
    @endif
    @hasSection('twitter_creator')
        <meta name="twitter:creator" content="@yield('twitter_creator')">
    @endif

    <!-- Mobile & Theme -->
    <meta name="theme-color" content="@yield('theme_color', '#1f2937')">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MHR">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- DNS Prefetch & Preconnect for Performance -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" defer>

    <!-- Structured Data (JSON-LD) -->
    @stack('structured_data')

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="antialiased bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    <!-- Header -->
    <x-header />

    <!-- Main Content -->
    <main id="main-content" class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Cart Sidebar -->
    <x-cart-sidebar />

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
