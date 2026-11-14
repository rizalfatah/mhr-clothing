@extends('moment.layout')
@section('title', $package['name'].' — Sepulang Moment')
@section('description', $package['description'].' Fotografi wisuda Yogyakarta bersama Sepulang Moment.')
@section('body-class', 'package-page package-page--'.$packageSlug)
@section('content')
    <article class="package-detail">
        <div class="package-hero">
            <img src="{{ asset($package['image']) }}" alt="{{ $package['image_alt'] }}" width="1200" height="1500" fetchpriority="high" style="object-position: {{ $package['position'] }}">
            <a class="package-back" href="{{ route('moment.home', [], false) }}#packages"><span aria-hidden="true">←</span> Semua paket</a>
            <div class="package-title"><span>Moments</span><h1>{{ $package['title'] }}</h1></div>
        </div>
        <section class="benefit-section" aria-labelledby="benefit-title">
            <h2 class="benefit-label" id="benefit-title">Benefit</h2>
            <div class="benefit-content">
                <p class="package-eyebrow">{{ $package['eyebrow'] }}</p>
                <ul class="benefit-list">@foreach($package['benefits'] as $benefit)<li>{{ $benefit }}</li>@endforeach</ul>
                <details class="price-details">
                    <summary class="pill-button">PRICE <span aria-hidden="true">+</span></summary>
                    <div class="price-content">
                        <span class="price-caption">{{ $prices_confirmed ? 'Mulai dari' : 'Estimasi paket' }}</span>
                        <p class="package-price">Rp {{ number_format($package['price'], 0, ',', '.') }}</p>
                        @unless($prices_confirmed)<p class="price-note">Estimasi awal untuk preview. Hubungi tim kami untuk harga final dan ketersediaan jadwal.</p>@endunless
                        <a class="booking-button" href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya tertarik dengan paket '.$package['name'].'. Boleh info harga dan jadwal foto wisuda?') }}" target="_blank" rel="noopener">Booking via WhatsApp @include('moment.partials.icon', ['icon' => 'arrow'])</a>
                    </div>
                </details>
                <a class="consultation-link" href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya ingin konsultasi paket '.$package['name'].'.') }}" target="_blank" rel="noopener">Tanya jadwal & konsultasi <span aria-hidden="true">↗</span></a>
            </div>
        </section>
        @if(count($package['gallery']))
            <section class="package-gallery" aria-label="Galeri {{ $package['name'] }}" data-gallery>
                <div class="gallery-heading"><span>A MOMENT TO REMEMBER</span><span data-gallery-counter>01 / {{ sprintf('%02d', count($package['gallery'])) }}</span></div>
                <button class="gallery-main" type="button" data-lightbox data-gallery-main aria-label="Perbesar foto {{ $package['name'] }}">
                    <img src="{{ asset($package['gallery'][0]) }}" alt="Contoh suasana foto wisuda {{ $package['title'] }}" width="800" height="1000" loading="lazy" data-gallery-image>
                    <span class="gallery-expand">@include('moment.partials.icon', ['icon' => 'expand'])</span>
                </button>
                <div class="gallery-thumbnails">@foreach($package['gallery'] as $photo)<button type="button" class="gallery-thumbnail {{ $loop->first ? 'is-active' : '' }}" data-gallery-thumbnail data-src="{{ asset($photo) }}" data-index="{{ $loop->index }}" aria-label="Lihat foto {{ $loop->iteration }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"><img src="{{ asset($photo) }}" alt="" width="120" height="120" loading="lazy"></button>@endforeach</div>
            </section>
        @endif
    </article>
@endsection
