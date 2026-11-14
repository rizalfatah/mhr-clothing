<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#001443">
    <title>@yield('title', 'Sepulang Moment — Every Graduation Has a Story')</title>
    <meta name="description" content="@yield('description', 'Fotografi wisuda di Yogyakarta. Abadikan cerita kelulusanmu bersama Sepulang Moment × MAHRM.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Sepulang Moment')">
    <meta property="og:description" content="Fotografi wisuda di Yogyakarta. Cerita kamu, kenangan selamanya.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset($hero) }}">
    <link rel="icon" href="{{ asset('moment-favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/moment.css', 'resources/js/moment.js'])
</head>
<body class="moment-site @yield('body-class')">
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    @include('moment.partials.header')
    <main id="main-content">@yield('content')</main>
    @include('moment.partials.footer')
    <dialog class="photo-dialog" id="photo-dialog" aria-label="Foto diperbesar">
        <form method="dialog"><button class="icon-button photo-dialog__close" aria-label="Tutup foto">×</button></form>
        <div class="lightbox-stage" data-lightbox-stage></div>
    </dialog>
    <dialog class="info-dialog" id="info-dialog" aria-labelledby="info-dialog-title">
        <form method="dialog"><button class="icon-button" aria-label="Tutup informasi">×</button></form>
        <p class="eyebrow">Sepulang Moment</p>
        <h2 id="info-dialog-title"></h2>
        <div data-info-content></div>
    </dialog>
</body>
</html>
