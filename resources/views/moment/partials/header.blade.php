<header class="site-header">
    <div class="site-header__inner">
        <a class="brand-link" href="{{ route('moment.home', [], false) }}" aria-label="Sepulang Moment — Beranda">@include('moment.partials.wordmark')</a>
        <button class="menu-toggle icon-button" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="moment-navigation" data-menu-toggle>
            <span></span><span></span><span></span>
        </button>
        <nav class="site-navigation" id="moment-navigation" aria-label="Menu utama" data-navigation>
            <a href="{{ route('moment.home', [], false) }}" @if(request()->routeIs('moment.home')) aria-current="page" @endif>Beranda <span>01</span></a>
            <a href="{{ route('moment.exclusive', [], false) }}" @if(request()->routeIs('moment.exclusive')) aria-current="page" @endif>Moment Exclusive <span>02</span></a>
            <a href="{{ route('moment.portrait', [], false) }}" @if(request()->routeIs('moment.portrait')) aria-current="page" @endif>Moment Portrait <span>03</span></a>
            <a href="{{ route('moment.group-portrait', [], false) }}" @if(request()->routeIs('moment.group-portrait')) aria-current="page" @endif>Group Portrait <span>04</span></a>
            <a href="{{ route('moment.presets', [], false) }}" @if(request()->routeIs('moment.presets')) aria-current="page" @endif>Preset & Desain <span>05</span></a>
            <a class="navigation-contact" href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya ingin konsultasi foto wisuda.') }}" target="_blank" rel="noopener">Let’s make a moment @include('moment.partials.icon', ['icon' => 'arrow'])</a>
        </nav>
    </div>
</header>
