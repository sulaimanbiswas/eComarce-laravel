@php
    $bannerColumns = (array) ($section->data->columns ?? []);
    $bannerImages = (array) ($bannerColumns['image'] ?? []);
    $bannerWidths = (array) ($bannerColumns['width'] ?? []);
    $bannerRatio = $section->data->banner_ratio ?? 'auto';
@endphp
@if (($section->data->banner_enabled ?? true) && collect($bannerImages)->filter()->isNotEmpty())
    @once
        @push('styles')
            <style>
                .promotion-banners { padding: var(--banner-space) 0; background: var(--banner-background); }
                .promotion-banners__container { max-width: 1536px; margin: 0 auto; padding: 0 clamp(20px, 3vw, 48px); }
                .promotion-banners__grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: clamp(14px, 1.5vw, 24px); align-items: stretch; }
                .promotion-banners__item { position: relative; display: block; grid-column: span var(--banner-width); min-width: 0; overflow: hidden; border-radius: var(--banner-radius); background: #f1eee7; text-decoration: none; isolation: isolate; }
                .promotion-banners__item picture { display: block; width: 100%; height: 100%; }
                .promotion-banners__item img { display: block; width: 100%; height: 100%; object-fit: var(--banner-fit); transition: transform .35s ease; }
                .promotion-banners--auto .promotion-banners__item img { height: auto; }
                .promotion-banners--wide .promotion-banners__item { aspect-ratio: 4.6 / 1; }
                .promotion-banners--landscape .promotion-banners__item { aspect-ratio: 3.8 / 1; }
                .promotion-banners__item:focus-visible { outline: 3px solid #b78931; outline-offset: 4px; }
                @media (hover: hover) and (pointer: fine) { .promotion-banners__item:hover img { transform: scale(1.015); } }
                @media (max-width: 767px) {
                    .promotion-banners { padding: min(var(--banner-space), 20px) 0; }
                    .promotion-banners__container { padding: 0 16px; }
                    .promotion-banners__item { grid-column: 1 / -1; }
                    .promotion-banners__item:has(source[data-has-mobile="true"]) { aspect-ratio: auto; }
                    .promotion-banners__item:has(source[data-has-mobile="true"]) img { height: auto; }
                }
                @media (prefers-reduced-motion: reduce) { .promotion-banners__item img { transition: none; } .promotion-banners__item:hover img { transform: none; } }
            </style>
        @endpush
    @endonce
    <section class="promotion-banners promotion-banners--{{ in_array($bannerRatio, ['auto', 'wide', 'landscape']) ? $bannerRatio : 'auto' }}" aria-label="{{ $section->title }}" style="--banner-space: {{ min(64, max(12, (int) ($section->data->banner_spacing ?? 24))) }}px; --banner-radius: {{ min(32, max(0, (int) ($section->data->banner_radius ?? 16))) }}px; --banner-background: {{ $section->data->banner_background ?? '#fbf9f5' }}; --banner-fit: {{ ($section->data->banner_fit ?? 'cover') === 'contain' ? 'contain' : 'cover' }};">
        <div class="promotion-banners__container"><div class="promotion-banners__grid">
            @foreach ($bannerWidths as $index => $width)
                @if (!empty($bannerImages[$index]))
                    @php
                        $link = data_get($bannerColumns, 'link.'.$index);
                        $filterCategories = implode(',', (array) data_get($bannerColumns, 'categories.'.$index, []));
                        $link = filled($link) && $link !== '#' ? url($link) : route('products.index', $filterCategories ? ['filter_category' => $filterCategories] : []);
                        $mobileImage = data_get($bannerColumns, 'mobile_image.'.$index);
                        $description = data_get($bannerColumns, 'alt.'.$index) ?: $section->title.' '.($loop->index + 1);
                    @endphp
                    <a class="promotion-banners__item" href="{{ $link }}" style="--banner-width: {{ in_array((int) $width, [3, 4, 6, 12]) ? (int) $width : 12 }};" @if ($section->data->banner_new_tab ?? false) target="_blank" rel="noopener noreferrer" @endif>
                        <picture>
                            @if ($mobileImage)<source media="(max-width: 767px)" srcset="{{ asset($mobileImage) }}" data-has-mobile="true">@endif
                            <img src="{{ asset($bannerImages[$index]) }}" alt="{{ $description }}" loading="lazy" decoding="async">
                        </picture>
                    </a>
                @endif
            @endforeach
        </div></div>
    </section>
@endif
