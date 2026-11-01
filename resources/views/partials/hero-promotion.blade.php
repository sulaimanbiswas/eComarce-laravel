@php
    $heroBannerCategory = categories()->first();
    $heroBannerDestination = ($company->hero_banner_link ?? null) ?: ($heroBannerCategory ? route('categories.products', $heroBannerCategory->slug) : route('products.index'));
@endphp
@if ($company->hero_banner_enabled ?? true)
    <a class="storefront-hero__promotion {{ ($mobilePromotion ?? false) ? 'storefront-hero__promotion--mobile' : 'storefront-hero__promotion--desktop' }}" href="{{ $heroBannerDestination }}" aria-label="{{ ($company->hero_banner_alt ?? null) ?: 'Explore our featured collection' }}">
        @if (!empty($logo->hero_banner))
            <img src="{{ asset($logo->hero_banner) }}" alt="{{ ($company->hero_banner_alt ?? null) ?: 'Featured store promotion' }}" width="600" height="490" loading="{{ ($mobilePromotion ?? false) ? 'lazy' : 'eager' }}" decoding="async">
        @else
            <div class="storefront-hero__promotion-copy">
                <span class="storefront-hero__eyebrow">Discover something new</span>
                <h2>{{ $heroBannerCategory->name ?? 'Made for your everyday.' }}</h2>
                <span class="storefront-hero__promotion-link">Explore collection <span aria-hidden="true">&rarr;</span></span>
            </div>
            @if (!empty($heroBannerCategory->image_id))
                <img class="storefront-hero__category-art" src="{{ asset($heroBannerCategory->image_src) }}" alt="" width="240" height="240" loading="lazy" decoding="async">
            @else
                <span class="storefront-hero__promotion-orbit" aria-hidden="true"></span>
            @endif
        @endif
    </a>
@endif
