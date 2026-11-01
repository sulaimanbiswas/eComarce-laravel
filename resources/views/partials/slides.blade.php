@php
    $heroSlides = slides();
    $heroCategory = categories()->first();
    $showHeroBanner = $company->hero_banner_enabled ?? true;
    $heroBannerLink = $company->hero_banner_link ?? null;
    $heroBannerLink = filled($heroBannerLink) ? $heroBannerLink : ($heroCategory ? route('categories.products', $heroCategory->slug) : route('products.index'));
@endphp

@push('head')
    @if ($firstHeroSlide = $heroSlides->first())
        <link rel="preload" as="image" href="{{ asset($firstHeroSlide->desktop_src) }}" media="(min-width: 768px)" fetchpriority="high">
        <link rel="preload" as="image" href="{{ asset($firstHeroSlide->mobile_src ?: $firstHeroSlide->desktop_src) }}" media="(max-width: 767px)" fetchpriority="high">
    @endif
@endpush

@push('styles')
    @include('partials.hero-styles')
@endpush

<section class="storefront-hero" aria-label="Featured promotions">
    <div class="storefront-hero__container">
        <div class="storefront-hero__grid {{ $showHeroBanner ? '' : 'storefront-hero__grid--single' }}">
            <div class="storefront-hero__slider block-slideshow" aria-roledescription="carousel" aria-label="Store promotions">
                @if ($heroSlides->isNotEmpty())
                    <div class="owl-carousel">
                        @foreach ($heroSlides as $slide)
                            <a class="storefront-hero__slide" href="{{ $slide->btn_href ?: route('products.index') }}" aria-label="{{ strip_tags($slide->title ?: 'Explore promotion '.($loop->index + 1)) }}" style="--hero-image-fit: {{ in_array($slide->object_fit, ['contain', 'cover', 'fill', 'none', 'scale-down']) ? $slide->object_fit : 'cover' }};">
                                <picture>
                                    <source media="(max-width: 767px)" srcset="{{ asset($slide->mobile_src ?: $slide->desktop_src) }}">
                                    <img src="{{ asset($slide->desktop_src) }}" alt="{{ strip_tags($slide->title ?: 'Featured store promotion') }}" width="1200" height="480" loading="{{ $loop->first ? 'eager' : 'lazy' }}" fetchpriority="{{ $loop->first ? 'high' : 'auto' }}" decoding="async">
                                </picture>
                                @if (filled($slide->title) || filled($slide->text) || filled($slide->btn_name))
                                    <div class="storefront-hero__caption">
                                        @if (filled($slide->title))<h2>{!! $slide->title !!}</h2>@endif
                                        @if (filled($slide->text))<div class="storefront-hero__description">{!! $slide->text !!}</div>@endif
                                        @if (filled($slide->btn_name))<span class="storefront-hero__cta">{{ $slide->btn_name }} <span aria-hidden="true">&rarr;</span></span>@endif
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    @if ($heroSlides->count() > 1)
                        <button class="storefront-hero__pause" type="button" aria-label="Pause slideshow" aria-pressed="false" data-paused="false">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path class="hero-pause-icon" d="M7 5h3v14H7zm7 0h3v14h-3z"/><path class="hero-play-icon" d="m8 5 11 7-11 7z"/></svg>
                        </button>
                    @endif
                @else
                    <div class="storefront-hero__welcome">
                        <div class="storefront-hero__welcome-copy">
                            <span class="storefront-hero__eyebrow">{{ $company->name ?? config('app.name') }}</span>
                            <h1>{{ ($company->home_heading ?? null) ?: 'Find your next favourite.' }}</h1>
                            <p>{{ ($company->tagline ?? null) ?: 'Explore our collection and discover something for you.' }}</p>
                            <a class="storefront-hero__cta" href="{{ route('products.index') }}" wire:navigate.hover>Explore the shop <span aria-hidden="true">&rarr;</span></a>
                        </div>
                        <div class="storefront-hero__art" aria-hidden="true">
                            <svg viewBox="0 0 240 260" fill="none"><ellipse cx="120" cy="237" rx="86" ry="12" fill="currentColor" opacity=".08"/><rect x="40" y="83" width="160" height="150" rx="24" fill="white"/><path d="M80 100V70a40 40 0 0 1 80 0v30" stroke="currentColor" stroke-width="8" stroke-linecap="round"/><path d="m91 157 20 20 40-44" stroke="currentColor" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="198" cy="54" r="19" fill="currentColor" opacity=".12"/><path d="M32 44v20M22 54h20M205 177v16M197 185h16" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                        </div>
                    </div>
                @endif
            </div>

            @if ($showHeroBanner)
                <a class="storefront-hero__promotion" href="{{ $heroBannerLink }}" aria-label="{{ $company->hero_banner_alt ?? 'Explore our featured collection' }}">
                    @if (!empty($logo->hero_banner))
                        <img src="{{ asset($logo->hero_banner) }}" alt="{{ $company->hero_banner_alt ?? 'Featured store promotion' }}" width="600" height="490" loading="eager" decoding="async">
                    @else
                        <div class="storefront-hero__promotion-copy">
                            <span class="storefront-hero__eyebrow">Discover something new</span>
                            <h2>{{ $heroCategory->name ?? 'Made for your everyday.' }}</h2>
                            <span class="storefront-hero__promotion-link">Explore collection <span aria-hidden="true">&rarr;</span></span>
                        </div>
                        @if (!empty($heroCategory->image_id))
                            <img class="storefront-hero__category-art" src="{{ asset($heroCategory->image_src) }}" alt="" width="240" height="240" decoding="async">
                        @else
                            <span class="storefront-hero__promotion-orbit" aria-hidden="true"></span>
                        @endif
                    @endif
                </a>
            @endif
        </div>
    </div>
</section>
