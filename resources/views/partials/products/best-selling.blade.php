@php
    $showcaseProducts = $products->take(min(48, max(1, (int) ($section->data->showcase_limit ?? 6))))->values();
    $showcaseHeading = ($section->data->showcase_heading ?? null) ?: $section->title;
    $productLayout = $section->storefrontLayout();
@endphp
@if ($showcaseProducts->isNotEmpty())
    @once
        @push('styles')
            @include('partials.products.best-selling-styles')
            @include('partials.products.section-layout-styles')
        @endpush
    @endonce
    <section class="best-selling product-section product-section--{{ $productLayout }}" data-columns="{{ min(6, max(2, (int) ($section->data->showcase_columns ?? 5))) }}" aria-labelledby="best-selling-heading-{{ $section->id }}" style="--showcase-background: {{ $section->data->showcase_background ?? '#eaf2e8' }}; --showcase-accent: {{ $section->data->showcase_accent ?? '#c89536' }}; --section-columns: {{ min(6, max(2, (int) ($section->data->showcase_columns ?? 5))) }};">
        <div class="best-selling__container">
            @include('partials.section-heading', ['headingSection' => $section, 'headingTitle' => $showcaseHeading, 'headingId' => 'best-selling-heading-'.$section->id, 'headingUrl' => route('home-sections.products', $section), 'headingPosition' => 'top'])
            @if ($productLayout !== 'showcase')
                @if ($productLayout === 'slider')
                    <div class="product-section__slider" x-data="{ start: true, end: false, overflow: false, measure() { const t = this.$refs.track; this.overflow = t.scrollWidth > t.clientWidth + 2; this.start = t.scrollLeft < 2; this.end = t.scrollLeft >= t.scrollWidth - t.clientWidth - 2; }, move(direction) { const t = this.$refs.track; t.scrollBy({ left: direction * t.clientWidth, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' }); } }" x-init="$nextTick(() => measure())" @resize.window.debounce="measure()">
                        <button class="product-section__arrow product-section__arrow--previous" type="button" x-show="overflow" style="display:none" :disabled="start" @click="move(-1)" aria-label="Previous products">&lsaquo;</button>
                        <div class="product-section__track" x-ref="track" @scroll.passive="measure()" role="group" aria-label="{{ $showcaseHeading }} products">
                            @foreach ($showcaseProducts as $product)<div class="product-section__item">@include('partials.products.showcase-card', ['product' => $product])</div>@endforeach
                        </div>
                        <button class="product-section__arrow product-section__arrow--next" type="button" x-show="overflow" style="display:none" :disabled="end" @click="move(1)" aria-label="Next products">&rsaquo;</button>
                    </div>
                @else
                    <div class="product-section__grid">@foreach ($showcaseProducts as $product)@include('partials.products.showcase-card', ['product' => $product])@endforeach</div>
                @endif
            @else
            <div class="best-selling__layout {{ $showcaseProducts->count() < 4 ? 'best-selling__layout--compact' : '' }}">
                @if ($showcaseProducts->count() >= 4)
                    <div class="best-selling__featured">
                        @include('partials.products.showcase-card', ['product' => $showcaseProducts->first()])
                    </div>
                    <div class="best-selling__collection">
                        @foreach ($showcaseProducts->skip(1) as $product)
                            <div class="best-selling__cell {{ $loop->index < 2 ? 'best-selling__cell--horizontal' : '' }}" @if ($loop->index >= 2) style="--showcase-card-span: {{ $showcaseProducts->count() === 4 ? 6 : ($showcaseProducts->count() === 5 ? 3 : 2) }};" @endif>
                                @include('partials.products.showcase-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                @else
                    @foreach ($showcaseProducts as $product)
                        <div class="best-selling__cell">
                            @include('partials.products.showcase-card', ['product' => $product])
                        </div>
                    @endforeach
                @endif
            </div>
            @endif
            @include('partials.section-heading', ['headingSection' => $section, 'headingTitle' => $showcaseHeading, 'headingId' => 'best-selling-heading-'.$section->id, 'headingUrl' => route('home-sections.products', $section), 'headingPosition' => 'bottom'])
        </div>
    </section>
@endif
