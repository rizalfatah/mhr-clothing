@extends('moment.layout')
@section('title', 'Halaman Tidak Ditemukan — Sepulang Moment')
@section('content')
<section class="preset-cta content-container">
    <p class="eyebrow">404 / A LITTLE DETOUR</p>
    <h1>Sepertinya kita<br><em>sedikit tersesat.</em></h1>
    <p>Halaman yang kamu cari tidak tersedia.<br>Mari kembali ke cerita wisudamu.</p>
    <a class="pill-button" href="{{ route('moment.home', [], false) }}">Kembali ke beranda</a>
</section>
@endsection
