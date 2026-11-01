@php
    $footerName = $company->name ?? config('app.name');
    $footerPhone = $company->phone ?? '';
    $footerEmail = $company->email ?? '';
    $footerVariant = array_key_exists($company->footer_variant ?? '', config('storefront.footer_variants')) ? $company->footer_variant : 'signature';
    $footerWhatsapp = preg_replace('/\D/', '', $company->whatsapp ?? '');
    if (strlen($footerWhatsapp) === 11 && str_starts_with($footerWhatsapp, '01')) {
        $footerWhatsapp = '88'.$footerWhatsapp;
    }
@endphp
<footer class="site__footer modern-footer modern-footer--{{ $footerVariant }}" aria-label="Store information">
    <div class="modern-footer__container">
        @if (($company->footer_support_enabled ?? true) && (filled($footerPhone) || filled($footerEmail)))
            <div class="modern-footer__support">
                <div class="modern-footer__support-copy">
                    <span class="modern-footer__eyebrow">WE'RE HERE TO HELP</span>
                    <h2>{{ ($company->footer_support_heading ?? null) ?: "Let's make shopping simple." }}</h2>
                    <p>{{ ($company->footer_support_text ?? null) ?: 'Questions about a product, your order or delivery? Get in touch.' }}</p>
                </div>
                <div class="modern-footer__support-actions">
                    @if (filled($footerPhone))
                        <a class="modern-footer__contact-button" href="tel:{{ $footerPhone }}" data-contact-type="tel">@include('partials.footer-icon', ['icon' => 'phone'])<span>{{ $footerPhone }}</span></a>
                    @else
                        <a class="modern-footer__contact-button" href="mailto:{{ $footerEmail }}">@include('partials.footer-icon', ['icon' => 'mail'])<span>Email us</span></a>
                    @endif
                    @if (filled($footerWhatsapp))<a class="modern-footer__contact-button modern-footer__contact-button--outline" href="https://wa.me/{{ $footerWhatsapp }}" target="_blank" rel="noopener noreferrer">WhatsApp @include('partials.footer-icon', ['icon' => 'arrow'])</a>@endif
                </div>
            </div>
        @endif

        <div class="modern-footer__grid {{ $menuItems->isEmpty() ? 'modern-footer__grid--compact' : '' }}">
            <div class="modern-footer__brand">
                <a class="modern-footer__logo" href="{{ url('/') }}" wire:navigate.hover aria-label="{{ $footerName }} home">
                    @if (filled($logo->desktop ?? null))<img src="{{ asset($logo->desktop) }}" alt="{{ $footerName }}" width="200" height="48" loading="lazy" decoding="async">@else<span>{{ $footerName }}</span>@endif
                </a>
                <p class="modern-footer__about">{{ $company->tagline ?? '' }}</p>
                <div class="modern-footer__socials" aria-label="Follow {{ $footerName }}">
                    @foreach ($social ?? [] as $platform => $socialData)
                        @if (filled($socialData->link ?? null) && $socialData->link !== '#')
                            <a href="{{ url($socialData->link) }}" aria-label="{{ ucfirst($platform) }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-{{ $platform === 'facebook' ? 'facebook-f' : ($platform === 'twitter' ? 'twitter' : $platform) }}" aria-hidden="true"></i></a>
                        @endif
                    @endforeach
                    @if (filled($footerEmail))<a href="mailto:{{ $footerEmail }}" aria-label="Email {{ $footerName }}">@include('partials.footer-icon', ['icon' => 'mail'])</a>@endif
                    @if (filled($footerPhone))<a href="tel:{{ $footerPhone }}" aria-label="Call {{ $footerName }}">@include('partials.footer-icon', ['icon' => 'phone'])</a>@endif
                </div>
            </div>

            <nav class="modern-footer__navigation" aria-label="Explore the store">
                <h3>Explore</h3>
                <ul>
                    <li><a href="{{ route('products.index') }}" wire:navigate.hover>Shop all products</a></li>
                    <li><a href="{{ route('categories') }}" wire:navigate.hover>Categories</a></li>
                    <li><a href="{{ route('brands') }}" wire:navigate.hover>Brands</a></li>
                    <li><a href="{{ route('cart') }}" wire:navigate.hover>Your cart</a></li>
                    @if ($show_option->track_order ?? false)<li><a href="{{ route('track-order') }}" wire:navigate.hover>Track your order</a></li>@endif
                </ul>
            </nav>

            @if ($menuItems->isNotEmpty())
                <nav class="modern-footer__navigation" aria-label="Store information links">
                    <h3>Helpful links</h3>
                    <ul>
                        @foreach ($menuItems as $item)
                            @php
                                $isExternal = \Illuminate\Support\Str::startsWith($item->href, ['http://', 'https://', 'mailto:', 'tel:', '#']);
                            @endphp
                            <li><a href="{{ $isExternal ? $item->href : url($item->href) }}" @unless ($isExternal) wire:navigate.hover @endunless>{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <div class="modern-footer__contact">
                <h3>Stay in touch</h3>
                <ul>
                    @if (filled($footerPhone))<li>@include('partials.footer-icon', ['icon' => 'phone'])<a href="tel:{{ $footerPhone }}">{{ $footerPhone }}</a></li>@endif
                    @if (filled($footerEmail))<li>@include('partials.footer-icon', ['icon' => 'mail'])<a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></li>@endif
                    @if (filled($company->address ?? null))<li>@include('partials.footer-icon', ['icon' => 'pin'])<address>{{ $company->address }}</address></li>@endif
                    @if (filled($company->office_time ?? null))<li>@include('partials.footer-icon', ['icon' => 'clock'])<span>{{ $company->office_time }}</span></li>@endif
                </ul>
            </div>
        </div>

        <div class="modern-footer__bottom">
            <p>&copy; {{ date('Y') }} {{ $footerName }}. All rights reserved.</p>
            @if (filled($company->dev_name ?? null) && $company->dev_name !== '#' && filled($company->dev_link ?? null) && $company->dev_link !== '#')<p>Developed by <a href="{{ $company->dev_link }}" target="_blank" rel="noopener noreferrer">{{ $company->dev_name }}</a></p>@endif
            <a class="modern-footer__top" href="#store-top">Back to top @include('partials.footer-icon', ['icon' => 'up'])</a>
        </div>
    </div>
</footer>
