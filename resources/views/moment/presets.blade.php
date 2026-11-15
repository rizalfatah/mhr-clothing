@extends('moment.layout')
@section('title', $copy['seo_presets_title'])
@section('description', $copy['seo_presets_description'])
@section('body-class', 'presets-page')
@section('content')
    <section class="presets-intro content-container">
        <a class="back-link" href="{{ route('moment.home', [], false) }}">← Kembali ke beranda</a>
        <p class="eyebrow">{{ $copy['text_39'] }}</p>
        <h1>{{ $copy['text_40'] }}<br><em>{{ $copy['text_41'] }}</em></h1>
        <p>{{ $copy['text_42'] }}</p>
        <span class="intro-spark" aria-hidden="true">✦</span>
    </section>
    <section class="preset-catalog content-container" aria-labelledby="preset-catalog-title" data-preset-catalog>
        <div class="catalog-heading"><h2 id="preset-catalog-title">{{ $copy['text_43'] }}</h2><span>{{ $copy['text_44'] }}</span></div>
        <div class="filter-controls" role="group" aria-label="Filter koleksi">
            <button type="button" class="is-active" data-filter="all" aria-pressed="true">{{ $copy['text_45'] }}</button>
            <button type="button" data-filter="preset" aria-pressed="false">{{ $copy['text_46'] }}</button>
            <button type="button" data-filter="design" aria-pressed="false">{{ $copy['text_47'] }}</button>
        </div>
        <p class="visually-hidden" data-filter-status aria-live="polite">{{ count($presets) }} koleksi ditampilkan</p>
        <div class="preset-grid">
            @foreach($presets as $preset)
                <article class="preset-card" data-preset-item data-type="{{ $preset['type'] }}">
                    <button type="button" class="preset-preview preset-preview--{{ $preset['style'] }}" data-lightbox aria-label="Perbesar preview {{ $preset['name'] }}">
                        <img src="{{ \App\Services\MomentContent::imageUrl($preset['image']) }}" alt="Preview {{ $preset['name'] }}" width="600" height="800" loading="lazy" style="object-position: {{ $mediaPositions[$preset['image']] ?? '50% 50%' }}">
                        @if($preset['style'] === 'editorial')<span class="editorial-overlay"><span>{{ $preset['overlay_top'] ?? $copy['text_49'] }}</span><strong>{{ $preset['overlay_title'] ?? $copy['text_50'] }}</strong><small>{{ $preset['overlay_caption'] ?? $copy['text_51'] }}</small></span>@endif
                        @if($preset['style'] === 'chapter')<span class="chapter-overlay">{{ $preset['overlay_title'] ?? $copy['text_52'] }}<small>{{ $preset['overlay_caption'] ?? $copy['text_53'] }}</small></span>@endif
                        <span class="preview-label">{{ $preset['label'] }}</span><span class="preview-expand">↗</span>
                    </button>
                    <div class="preset-copy"><span class="preset-number">0{{ $loop->iteration }} / {{ $preset['label'] }}</span><h3>{{ $preset['name'] }}</h3><p>{{ $preset['description'] }}</p><a href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya ingin tahu tentang '.$preset['label'].' '.$preset['name'].'.') }}" target="_blank" rel="noopener">{{ $copy['text_54'] }}<span aria-hidden="true">↗</span></a></div>
                </article>
            @endforeach
        </div>
    </section>
    <section class="preset-cta content-container"><span aria-hidden="true">✳</span><h2>{{ $copy['text_55'] }}<br><em>{{ $copy['text_56'] }}</em></h2><p>{{ $copy['text_57'] }}<br>{{ $copy['text_58'] }}</p><a class="pill-button" href="{{ $whatsappUrl }}?text={{ rawurlencode($consultation_template) }}" target="_blank" rel="noopener">{{ $copy['preset_cta'] }} @include('moment.partials.icon', ['icon' => 'arrow'])</a></section>
@endsection
