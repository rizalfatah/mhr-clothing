@extends('moment.layout')
@section('title', $copy['seo_not_found_title'])
@section('content')
<section class="preset-cta content-container">
    <p class="eyebrow">{{ $copy['text_24'] }}</p>
    <h1>{{ $copy['text_25'] }}<br><em>{{ $copy['text_26'] }}</em></h1>
    <p>{{ $copy['text_27'] }}<br>{{ $copy['text_28'] }}</p>
    <a class="pill-button" href="{{ route('moment.home', [], false) }}">{{ $copy['text_29'] }}</a>
</section>
@endsection
