@extends('moment.layout')
@section('title', $copy['seo_home_title'])
@section('description', $copy['seo_home_description'])
@section('body-class', 'home-page')
@section('content')
    <section class="home-hero" aria-label="Sepulang Moment, fotografi wisuda Yogyakarta">
        <img class="home-hero__photo" src="{{ \App\Services\MomentContent::imageUrl($hero) }}" alt="{{ $hero_alt }}" style="object-position: {{ $mediaPositions[$hero] ?? '50% 50%' }}" width="2731" height="4096" fetchpriority="high">
        <div class="home-hero__signature">@include('moment.partials.wordmark')</div>
        <a class="hero-scroll" href="#about" aria-label="Kenali Sepulang Moment"><span>{{ $copy['text_1'] }}</span><span aria-hidden="true">↓</span></a>
    </section>
    <section class="about-section content-container" id="about" aria-labelledby="about-title">
        <div class="about-copy">
            <h1 id="about-title">{{ $copy['text_2'] }}</h1>
            <h2>{{ $copy['text_3'] }}<br class="desktop-break">{{ $copy['text_4'] }}</h2>
            <p>{{ $copy['text_5'] }}</p>
            <p>{{ $copy['text_6'] }}</p>
        </div>
        <div class="bouquet-scene" aria-hidden="true"><div class="bouquet-tile">@if($bouquet_image)<img src="{{ \App\Services\MomentContent::imageUrl($bouquet_image) }}" alt="" width="142" height="142">@else<span>💐</span>@endif</div><span class="bouquet-note">{{ $copy['text_7'] }}</span></div>
    </section>
    @if(count($packages))
    <section class="package-picker" id="packages" aria-labelledby="package-picker-title" data-package-picker>
        <div class="budget-control">
            <label for="moment-budget">{{ $copy['text_8'] }}<strong>{{ $copy['text_9'] }}</strong></label>
            <div class="budget-display"><span>{{ $copy['text_10'] }}</span><output for="moment-budget" data-budget-output>{{ number_format($budget_initial,0,',','.') }}</output></div>
            <input type="range" id="moment-budget" min="{{ $budget_min }}" max="{{ $budget_max }}" step="{{ $budget_step }}" value="{{ $budget_initial }}" aria-valuetext="{{ number_format($budget_initial,0,',','.') }} rupiah" aria-describedby="moment-budget-hint" data-budget>
            <div class="budget-limits"><span>{{ number_format($budget_min,0,',','.') }}</span><span>{{ number_format($budget_max,0,',','.') }}</span></div>
            <p class="budget-hint" id="moment-budget-hint">Geser per Rp{{ number_format($budget_step,0,',','.') }}</p>
        </div>
        <div class="picker-heading"><h2 id="package-picker-title">{{ $copy['text_11'] }}<br>{{ $copy['text_12'] }}</h2><span class="budget-sticker" aria-hidden="true">{{ $copy['text_13'] }}<br><strong>{{ $copy['text_14'] }}</strong>↙</span></div>
        <p class="budget-status" data-budget-status role="status" aria-live="polite">{{ $copy['text_15'] }}</p>
        <div class="package-carousel" data-package-carousel aria-label="Pilihan paket foto wisuda" tabindex="0">
            @foreach($packages as $slug => $item)
                <a class="package-card {{ $slug === 'exclusive' ? 'is-recommended' : '' }}" href="{{ route('moment.packages.show', ['package'=>$slug], false) }}" data-package-card data-price="{{ $item['price'] }}" data-name="{{ $item['name'] }}" @if($slug === 'exclusive') data-recommended="true" @endif>
                    <img src="{{ \App\Services\MomentContent::imageUrl($item['image']) }}" alt="{{ $item['image_alt'] }}" width="600" height="800" loading="lazy" style="object-position: {{ $item['position'] }}">
                    <span class="package-card__tag" data-recommendation-tag @if($slug !== 'exclusive') hidden @endif>Pilihan untukmu ✦</span>
                    <div class="package-card__caption"><h3>{{ $item['name'] }}</h3><span>{{ $copy['view_package'] }} @include('moment.partials.icon', ['icon' => 'arrow'])</span></div>
                </a>
            @endforeach
        </div>
        <div class="carousel-controls"><button type="button" class="icon-button previous" data-carousel-previous aria-label="Paket sebelumnya">@include('moment.partials.icon', ['icon' => 'chevron'])</button><span>{{ $copy['text_18'] }}</span><button type="button" class="icon-button" data-carousel-next aria-label="Paket berikutnya">@include('moment.partials.icon', ['icon' => 'chevron'])</button></div>
        @unless($prices_confirmed)<p class="price-disclaimer">{{ $copy['text_20'] }}<br>{{ $copy['text_21'] }}</p>@endunless
    </section>
    @else
    <section class="preset-cta content-container"><a class="pill-button" href="{{ $whatsappUrl }}">{{ $copy['empty_packages'] }}</a></section>
    @endif
@endsection
