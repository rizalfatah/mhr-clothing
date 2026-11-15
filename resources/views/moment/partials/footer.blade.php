<footer class="site-footer" id="contact">
    <div class="footer-inner">
        <div class="brand-collaboration">
            <img src="{{ \App\Services\MomentContent::imageUrl($collaboration_logo) }}" alt="Sepulang Moment × MAHRM Culture" width="193" height="54" loading="lazy">
        </div>
        <div class="footer-columns">
            <section class="footer-contact" aria-labelledby="customer-center">
                <h2 id="customer-center">{{ $copy['text_60'] }}</h2>
                <a class="contact-line" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
                    @include('moment.partials.icon', ['icon' => 'pin'])<span>{{ $address }}</span>
                </a>
                <a class="contact-line" href="tel:{{ $phone }}">@include('moment.partials.icon', ['icon' => 'whatsapp'])<span>+{{ $phone }}</span></a>
            </section>
            <section class="footer-support" aria-labelledby="consumer-service">
                <h2 id="consumer-service">{{ $copy['text_63'] }}<br>{{ $copy['text_64'] }}</h2>
                <p>{{ $copy['text_65'] }}</p>
                <a href="{{ $whatsappUrl }}?text={{ rawurlencode($consultation_template) }}" target="_blank" rel="noopener">{{ $copy['contact_button'] }}</a>
                @if($instagram_url)
                    <a class="social-link" href="{{ $instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram Sepulang Moment">@include('moment.partials.icon', ['icon' => 'instagram'])</a>
                @endif
                @foreach(['facebook_url'=>'Facebook','threads_url'=>'Threads','tiktok_url'=>'TikTok'] as $key=>$label)
                    @if(!empty($$key))<a class="social-link" href="{{ $$key }}" target="_blank" rel="noopener">{{ $label }}</a>@endif
                @endforeach
            </section>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} {{ $brand_name }} × MAHRM<br>{{ $copy['text_67'] }}</p>
            <div><button type="button" class="text-button" data-info="terms">{{ $copy['text_68'] }}</button><span aria-hidden="true"> · </span><button type="button" class="text-button" data-info="privacy">{{ $copy['text_69'] }}</button></div>
        </div>
    </div>
</footer>
