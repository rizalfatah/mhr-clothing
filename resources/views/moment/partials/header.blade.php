<header class="site-header">
    <div class="site-header__inner">
        <a class="brand-link" href="{{ route('moment.home', [], false) }}" aria-label="Sepulang Moment — Beranda">@include('moment.partials.wordmark')</a>
        <button class="menu-toggle icon-button" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="moment-navigation" data-menu-toggle>
            <span></span><span></span><span></span>
        </button>
        <nav class="site-navigation" id="moment-navigation" aria-label="Menu utama" data-navigation>
            <a href="{{ route('moment.home', [], false) }}" @if(request()->routeIs('moment.home')) aria-current="page" @endif>{{ $copy['text_70'] }}<span>01</span></a>
            @foreach($packages as $slug=>$item)
            <a href="{{ route('moment.packages.show',['package'=>$slug],false) }}" @if(request()->path()===$slug) aria-current="page" @endif>{{ $item['name'] }} <span>{{ sprintf('%02d',$loop->iteration+1) }}</span></a>
            @endforeach
            <a href="{{ route('moment.presets', [], false) }}" @if(request()->routeIs('moment.presets')) aria-current="page" @endif>{{ $copy['text_74'] }}<span>05</span></a>
            <a class="navigation-contact" href="{{ $whatsappUrl }}?text={{ rawurlencode($consultation_template) }}" target="_blank" rel="noopener">{{ $copy['navigation_contact'] }} @include('moment.partials.icon', ['icon' => 'arrow'])</a>
        </nav>
    </div>
</header>
