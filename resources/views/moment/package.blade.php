@extends('moment.layout')
@section('title', $package['seo_title'] ?: $package['name'].' — '.$brand_name)
@section('description', $package['seo_description'] ?: $package['description'])
@section('body-class', 'package-page package-page--'.$packageSlug)
@section('content')
    <article class="package-detail">
        <div class="package-hero">
            <img src="{{ \App\Services\MomentContent::imageUrl($package['image']) }}" alt="{{ $package['image_alt'] }}" width="1200" height="1500" fetchpriority="high" style="object-position: {{ $package['position'] }}">
            <a class="package-back" href="{{ route('moment.home', [], false) }}#packages"><span aria-hidden="true">←</span>{{ $copy['text_30'] }}</a>
            <div class="package-title"><span>{{ $copy['text_31'] }}</span><h1>{{ $package['title'] }}</h1></div>
        </div>
        <section class="benefit-section" aria-labelledby="benefit-title">
            <h2 class="benefit-label" id="benefit-title">{{ $copy['text_32'] }}</h2>
            <div class="benefit-content">
                <p class="package-eyebrow">{{ $package['eyebrow'] }}</p>
                <ul class="benefit-list">@foreach($package['benefits'] as $benefit)<li>{{ $benefit }}</li>@endforeach</ul>
                <details class="price-details">
                    <summary class="pill-button">{{ $copy['text_33'] }}<span aria-hidden="true">+</span></summary>
                    <div class="price-content">
                        <span class="price-caption">{{ $package['prices_confirmed'] ? 'Mulai dari' : 'Estimasi paket' }}</span>
                        <p class="package-price">Rp {{ number_format($package['price'], 0, ',', '.') }}</p>
                        @unless($package['prices_confirmed'])<p class="price-note">{{ $copy['text_34'] }}</p>@endunless
                        <a class="booking-button" href="{{ $whatsappUrl }}?text={{ rawurlencode(strtr($booking_template,['{package_name}'=>$package['name'],'{price}'=>number_format($package['price'],0,',','.')])) }}" target="_blank" rel="noopener">{{ $copy['booking_button'] }} @include('moment.partials.icon', ['icon' => 'arrow'])</a>
                    </div>
                </details>
                <a class="consultation-link" href="{{ $whatsappUrl }}?text={{ rawurlencode($consultation_template) }}" target="_blank" rel="noopener">{{ $copy['text_36'] }}<span aria-hidden="true">↗</span></a>
            </div>
        </section>
        @if(count($package['gallery']))
            <section class="package-gallery" aria-label="Galeri {{ $package['name'] }}" data-gallery>
                <div class="gallery-heading"><span>{{ $copy['text_37'] }}</span><span data-gallery-counter>01 / {{ sprintf('%02d', count($package['gallery'])) }}</span></div>
                <button class="gallery-main" type="button" data-lightbox data-gallery-main aria-label="Perbesar foto {{ $package['name'] }}">
                    <img src="{{ \App\Services\MomentContent::imageUrl($package['gallery'][0]) }}" alt="Contoh suasana foto wisuda {{ $package['title'] }}" width="800" height="1000" loading="lazy" style="object-position: {{ $mediaPositions[$package['gallery'][0]] ?? '50% 50%' }}" data-gallery-image>
                    <span class="gallery-expand">@include('moment.partials.icon', ['icon' => 'expand'])</span>
                </button>
                <div class="gallery-thumbnails">@foreach($package['gallery'] as $photo)<button type="button" class="gallery-thumbnail {{ $loop->first ? 'is-active' : '' }}" data-gallery-thumbnail data-src="{{ \App\Services\MomentContent::imageUrl($photo) }}" data-position="{{ $mediaPositions[$photo] ?? '50% 50%' }}" data-index="{{ $loop->index }}" aria-label="Lihat foto {{ $loop->iteration }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"><img src="{{ \App\Services\MomentContent::imageUrl($photo) }}" alt="" width="120" height="120" loading="lazy"></button>@endforeach</div>
            </section>
        @endif
    </article>
@endsection
