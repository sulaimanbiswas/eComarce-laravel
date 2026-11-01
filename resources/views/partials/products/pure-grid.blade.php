<div class="block block-products-carousel">
    <div class="container">
        @if($title ?? null)
            @include('partials.section-heading', ['headingSection' => $section ?? null, 'headingTitle' => $title, 'headingId' => 'grid-heading-'.($section->id ?? 'products'), 'headingUrl' => isset($section) ? route('home-sections.products', $section) : route('products.index'), 'headingPosition' => 'top'])
        @endif
        <div class="products-view__list products-list" data-layout="grid-{{ $cols ?? 5 }}-full" data-with-features="false">
            <div class="products-list__body">
                @foreach($products as $product)
                    <div class="products-list__item">
                        @include('partials.products.item', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
        @if ($title ?? null)
            @include('partials.section-heading', ['headingSection' => $section ?? null, 'headingTitle' => $title, 'headingId' => 'grid-heading-'.($section->id ?? 'products'), 'headingUrl' => isset($section) ? route('home-sections.products', $section) : route('products.index'), 'headingPosition' => 'bottom'])
        @endif
    </div>
</div>
