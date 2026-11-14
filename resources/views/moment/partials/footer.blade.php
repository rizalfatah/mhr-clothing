<footer class="site-footer" id="contact">
    <div class="footer-inner">
        <div class="brand-collaboration">
            @include('moment.partials.wordmark')
            <span class="collaboration-times" aria-hidden="true">×</span>
            <span class="mahrm-wordmark">MAHRM<span>CULTURE</span></span>
        </div>
        <div class="footer-columns">
            <section class="footer-contact" aria-labelledby="customer-center">
                <h2 id="customer-center">Customer Center</h2>
                <a class="contact-line" href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($address) }}" target="_blank" rel="noopener">
                    @include('moment.partials.icon', ['icon' => 'pin'])<span>{{ $address }}</span>
                </a>
                <a class="contact-line" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">@include('moment.partials.icon', ['icon' => 'whatsapp'])<span>+{{ $whatsapp }}</span></a>
            </section>
            <section class="footer-support" aria-labelledby="consumer-service">
                <h2 id="consumer-service">Consumer Complaints<br>Service Contact Information</h2>
                <p>Punya pertanyaan atau masukan tentang layanan kami? Kami siap mendengarkan.</p>
                <a href="{{ $whatsappUrl }}?text={{ rawurlencode('Halo Sepulang Moment, saya ingin menyampaikan pertanyaan atau masukan terkait layanan.') }}" target="_blank" rel="noopener">Hubungi Customer Center ↗</a>
                @if($instagram_url)
                    <a class="social-link" href="{{ $instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram Sepulang Moment">@include('moment.partials.icon', ['icon' => 'instagram'])</a>
                @endif
            </section>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Sepulang Moment × MAHRM<br>All rights reserved</p>
            <div><button type="button" class="text-button" data-info="terms">Terms and Conditions</button><span aria-hidden="true"> · </span><button type="button" class="text-button" data-info="privacy">Privacy Policy</button></div>
        </div>
    </div>
</footer>
