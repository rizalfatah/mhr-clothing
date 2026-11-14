@extends('moment.layout')
@section('title', 'Sepulang Moment — Cerita Wisudamu, Selamanya')
@section('body-class', 'home-page')
@section('content')
    <section class="home-hero" aria-label="Sepulang Moment, fotografi wisuda Yogyakarta">
        <img class="home-hero__photo" src="{{ asset($hero) }}" alt="Potret wisudawati berhijab biru dengan latar cahaya malam" width="2731" height="4096" fetchpriority="high">
        <div class="home-hero__signature">@include('moment.partials.wordmark')</div>
        <a class="hero-scroll" href="#about" aria-label="Kenali Sepulang Moment"><span>SCROLL TO YOUR STORY</span><span aria-hidden="true">↓</span></a>
    </section>
    <section class="about-section content-container" id="about" aria-labelledby="about-title">
        <div class="about-copy">
            <h1 id="about-title">About</h1>
            <h2>Every Graduation Has a<br class="desktop-break"> Story Worth Remembering.</h2>
            <p>Sepulang Moment adalah layanan jasa fotografi yang berfokus pada dokumentasi momen wisuda di Yogyakarta.</p>
            <p>Kami percaya bahwa setiap perjalanan memiliki cerita yang layak dikenang. Karena itu, kami berusaha menghadirkan pengalaman pemotretan yang nyaman dengan hasil foto yang dapat menjadi kenangan berharga untuk waktu yang lama.</p>
        </div>
        <div class="bouquet-scene" aria-hidden="true"><div class="bouquet-tile"><span>💐</span></div><span class="bouquet-note">for your next chapter.</span></div>
    </section>
    <section class="package-picker" id="packages" aria-labelledby="package-picker-title" data-package-picker>
        <div class="budget-control">
            <label for="moment-budget">Your <strong>Budget</strong></label>
            <div class="budget-display"><span>IDR</span><output for="moment-budget" data-budget-output>300.000</output></div>
            <input type="range" id="moment-budget" min="150000" max="750000" step="50000" value="300000" aria-valuetext="300.000 rupiah" data-budget>
            <div class="budget-limits"><span>150rb</span><span>750rb</span></div>
        </div>
        <div class="picker-heading"><h2 id="package-picker-title">Pilih Paket<br>Moment Kamu</h2><span class="budget-sticker" aria-hidden="true">Sesuai<br><strong>Budget</strong>↙</span></div>
        <p class="budget-status" data-budget-status role="status" aria-live="polite">Moment Exclusive cocok dengan budget kamu.</p>
        <div class="package-carousel" data-package-carousel aria-label="Pilihan paket foto wisuda" tabindex="0">
            @foreach($packages as $slug => $item)
                <a class="package-card {{ $slug === 'exclusive' ? 'is-recommended' : '' }}" href="{{ route('moment.'.$slug, [], false) }}" data-package-card data-price="{{ $item['price'] }}" data-name="{{ $item['name'] }}" @if($slug === 'exclusive') data-recommended="true" @endif>
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['image_alt'] }}" width="600" height="800" loading="lazy" style="object-position: {{ $item['position'] }}">
                    <span class="package-card__tag" data-recommendation-tag @if($slug !== 'exclusive') hidden @endif>Pilihan untukmu ✦</span>
                    <div class="package-card__caption"><h3>{{ $item['name'] }}</h3><span>Lihat paket @include('moment.partials.icon', ['icon' => 'arrow'])</span></div>
                </a>
            @endforeach
        </div>
        <div class="carousel-controls"><button type="button" class="icon-button previous" data-carousel-previous aria-label="Paket sebelumnya">@include('moment.partials.icon', ['icon' => 'chevron'])</button><span>Temukan yang paling kamu banget.</span><button type="button" class="icon-button" data-carousel-next aria-label="Paket berikutnya">@include('moment.partials.icon', ['icon' => 'chevron'])</button></div>
        @unless($prices_confirmed)<p class="price-disclaimer">Pilihan budget menggunakan estimasi awal.<br>Konfirmasi harga final bersama tim kami.</p>@endunless
    </section>
@endsection
