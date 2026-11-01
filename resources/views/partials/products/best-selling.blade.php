@php
    $showcaseProducts = $products->take(min(12, max(1, (int) ($section->data->showcase_limit ?? 6))))->values();
    $showcaseHeading = ($section->data->showcase_heading ?? null) ?: 'Best Selling Products';
@endphp
@if ($showcaseProducts->isNotEmpty())
    @once
        @push('styles')
            @include('partials.products.best-selling-styles')
        @endpush
    @endonce
    <section class="best-selling" aria-labelledby="best-selling-heading-{{ $section->id }}" style="--showcase-background: {{ $section->data->showcase_background ?? '#eaf2e8' }}; --showcase-accent: {{ $section->data->showcase_accent ?? '#c89536' }};">
        <div class="best-selling__container">
            <h2 id="best-selling-heading-{{ $section->id }}" class="best-selling__heading"><a href="{{ route('home-sections.products', $section) }}" wire:navigate.hover>{{ $showcaseHeading }}</a></h2>
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
        </div>
    </section>
@endif
