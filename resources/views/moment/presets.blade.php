@extends('moment.layout')
@section('title', 'Preset & Desain — Sepulang Moment')
@section('body-class', 'presets-page')
@section('content')
    <section class="presets-intro content-container">
        <a class="back-link" href="{{ route('moment.home', [], false) }}">← Kembali ke beranda</a>
        <p class="eyebrow">THE FINISHING TOUCH</p>
        <h1>One moment.<br><em>Many ways to feel it.</em></h1>
        <p>Sentuhan warna dan desain untuk membuat cerita wisudamu terasa lebih kamu.</p>
        <span class="intro-spark" aria-hidden="true">✦</span>
    </section>
    <section class="preset-catalog content-container" aria-labelledby="preset-catalog-title" data-preset-catalog>
        <div class="catalog-heading"><h2 id="preset-catalog-title">Preset & Desain</h2><span>THE COLLECTION / 01</span></div>
        <div class="filter-controls" role="group" aria-label="Filter koleksi">
            <button type="button" class="is-active" data-filter="all" aria-pressed="true">Semua</button>
            <button type="button" data-filter="preset" aria-pressed="false">Preset</button>
            <button type="button" data-filter="design" aria-pressed="false">Desain</button>
        </div>
        <p class="visually-hidden" data-filter-status aria-live="polite">4 koleksi ditampilkan</p>
        <div class="preset-grid">
            @foreach($presets as $preset)
                <article class="preset-card" data-preset-item data-type="{{ $preset['type'] }}">
                    <button type="button" class="preset-preview preset-preview--{{ $preset['style'] }}" data-lightbox aria-label="Perbesar preview {{ $preset['name'] }}">
                        <img src="{{ asset($preset['image']) }}" alt="Preview {{ $preset['name'] }}" width="600" height="800" loading="lazy">
                        @if($preset['style'] === 'editorial')<span class="editorial-overlay"><span>the next</span><strong>chapter.</strong><small>A STORY WORTH REMEMBERING</small></span>@endif
                        @if($preset['style'] === 'chapter')<span class="chapter-overlay">Us, in this moment.<small>THE GRADUATION DIARIES</small></span>@endif
                        <span class="preview-label">{{ $preset['label'] }}</span><span class="preview-expand">↗</span>
                    </button>
                    <div class="preset-copy"><span class="preset-number">0{{ $loop->iteration }} / {{ $preset['label'] }}</span><h3>{{ $preset['name'] }}</h3><p>{{ $preset['description'] }}</p><a href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya ingin tahu tentang '.$preset['label'].' '.$preset['name'].'.') }}" target="_blank" rel="noopener">Tanya koleksi ini <span aria-hidden="true">↗</span></a></div>
                </article>
            @endforeach
        </div>
    </section>
    <section class="preset-cta content-container"><span aria-hidden="true">✳</span><h2>Let’s make it<br><em>yours.</em></h2><p>Belum menemukan nuansa yang pas?<br>Ceritakan idemu, kita mulai dari sana.</p><a class="pill-button" href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya ingin konsultasi preset dan desain untuk foto wisuda.') }}" target="_blank" rel="noopener">Ceritakan idemu @include('moment.partials.icon', ['icon' => 'arrow'])</a></section>
@endsection
