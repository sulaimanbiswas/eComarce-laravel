@php
    $headingDefaults = array_replace(config('storefront.heading_defaults'), (array) setting('section_heading'));
    $headingSection = $headingSection ?? null;
    $headingVariant = $headingSection->data->heading_variant ?? 'inherit';
    $headingVariant = $headingVariant === 'inherit' ? $headingDefaults['variant'] : $headingVariant;
    $headingVariant = array_key_exists($headingVariant, config('storefront.heading_variants')) ? $headingVariant : 'split';
    $headingPalette = $headingDefaults;
    if (($headingSection->data->heading_palette ?? 'inherit') === 'custom') {
        foreach (['title_color', 'link_color', 'link_hover', 'accent_color', 'link_background', 'link_border'] as $headingColor) {
            $headingPalette[$headingColor] = data_get($headingSection, 'data.heading_'.$headingColor) ?: $headingDefaults[$headingColor];
        }
    }
    $headingLinkText = ($headingSection->data->heading_link_text ?? null) ?: $headingDefaults['link_text'];
    $headingShowLink = ($headingShowLink ?? ($headingSection->data->showcase_view_all ?? true)) && filled($headingUrl ?? null);
    $headingPosition = $headingPosition ?? 'top';
@endphp
@once
    @push('styles')
        <style>
            .section-heading { --heading-gap: 28px; position: relative; display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 16px 24px; margin: 0 0 var(--heading-gap); padding-bottom: 16px; }
            .section-heading__title { position: relative; min-width: 0; padding: 0; margin: 0; color: var(--heading-title); font-size: clamp(22px, 2vw, 28px); font-weight: 650; line-height: 1.35; overflow-wrap: anywhere; }
            .section-heading__title::after { content: ''; display: block; width: 42px; height: 3px; border-radius: 2px; margin-top: 14px; background: var(--heading-accent); }
            .section-heading__title a, .section-heading__title a:hover { color: inherit; text-decoration: none; }
            .section-heading__link { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 9px 17px; min-height: 42px; border: 1px solid var(--heading-link-border); border-radius: 999px; background: var(--heading-link-background); color: var(--heading-link); text-decoration: none; font-size: 13px; font-weight: 500; line-height: 1.4; white-space: nowrap; align-self: start; transition: color .18s ease, border-color .18s ease; }
            .section-heading__link svg { flex-shrink: 0; transition: transform .18s ease; }
            .section-heading__link:hover { color: var(--heading-link-hover); border-color: currentColor; text-decoration: none; }
            .section-heading__link:focus-visible, .section-heading__title a:focus-visible { outline: 2px solid var(--heading-accent); outline-offset: 4px; }
            .section-heading--centered { grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr); }
            .section-heading--centered .section-heading__title { grid-column: 2; text-align: center; }
            .section-heading--centered .section-heading__link { grid-column: 3; justify-self: end; }
            .section-heading--centered .section-heading__title::after, .section-heading--stacked .section-heading__title::after, .section-heading--bottom .section-heading__title::after { margin-left: auto; margin-right: auto; }
            .section-heading--stacked, .section-heading--bottom { grid-template-columns: minmax(0, 1fr); justify-items: center; text-align: center; }
            .section-heading--ruled { border-bottom: 1px solid var(--heading-link-border); padding-bottom: 18px; }
            .section-heading--ruled .section-heading__title::after { position: absolute; bottom: -19px; margin: 0; }
            .section-heading--no-link:not(.section-heading--split):not(.section-heading--ruled) { grid-template-columns: minmax(0, 1fr); }
            .section-heading--no-link .section-heading__title { grid-column: 1; }
            .section-heading--footer { display: flex; justify-content: center; margin: 24px 0 0; padding: 0; }
            @media (hover: hover) and (pointer: fine) { .section-heading__link:hover svg { transform: translateX(3px); } }
            @media (max-width: 767px) {
                .section-heading { gap: 14px; margin-bottom: 22px; padding-bottom: 8px; }
                .section-heading__title { font-size: 22px; }
                .section-heading__link { min-height: 40px; padding: 8px 12px; font-size: 12px; gap: 7px; }
                .section-heading--centered { grid-template-columns: minmax(0, 1fr); justify-items: center; }
                .section-heading--centered .section-heading__title, .section-heading--centered .section-heading__link { grid-column: 1; justify-self: center; }
                .section-heading--ruled { padding-bottom: 18px; }
                .section-heading--footer { margin-top: 22px; margin-bottom: 0; }
            }
            @media (max-width: 359px) { .section-heading--split, .section-heading--ruled { grid-template-columns: minmax(0, 1fr); justify-items: start; } }
            @media (prefers-reduced-motion: reduce) { .section-heading__link, .section-heading__link svg { transition: none; }.section-heading__link:hover svg { transform: none; } }
        </style>
    @endpush
@endonce
@if ($headingPosition === 'top' || ($headingVariant === 'bottom' && $headingShowLink))
    <div class="section-heading section-heading--{{ $headingPosition === 'bottom' ? 'footer' : $headingVariant }} {{ !$headingShowLink ? 'section-heading--no-link' : '' }}" style="--heading-title: {{ $headingPalette['title_color'] }}; --heading-link: {{ $headingPalette['link_color'] }}; --heading-link-hover: {{ $headingPalette['link_hover'] }}; --heading-accent: {{ $headingPalette['accent_color'] }}; --heading-link-background: {{ $headingPalette['link_background'] }}; --heading-link-border: {{ $headingPalette['link_border'] }};">
        @if ($headingPosition === 'top')
            <h2 id="{{ $headingId }}" class="section-heading__title">@if (filled($headingUrl ?? null))<a href="{{ $headingUrl }}" wire:navigate.hover>{{ $headingTitle }}</a>@else{{ $headingTitle }}@endif</h2>
        @endif
        @if ($headingShowLink && (($headingPosition === 'top' && $headingVariant !== 'bottom') || $headingPosition === 'bottom'))
            <a class="section-heading__link" href="{{ $headingUrl }}" wire:navigate.hover><span>{{ $headingLinkText }}</span><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h16m-6-6 6 6-6 6"/></svg></a>
        @endif
    </div>
@endif
