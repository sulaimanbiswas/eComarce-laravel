<section class="best-selling product-section product-section--all-products" data-columns="{{ min(6, max(2, (int) ($section->data->showcase_columns ?? 5))) }}" aria-labelledby="all-products-heading-{{ $section->id }}" style="--showcase-background: {{ $section->data->showcase_background ?? '#fbf9f5' }}; --showcase-accent: {{ $section->data->showcase_accent ?? '#c89536' }}; --section-columns: {{ min(6, max(2, (int) ($section->data->showcase_columns ?? 5))) }};">
    @once
        @push('styles')
            @include('partials.products.best-selling-styles')
            @include('partials.products.section-layout-styles')
        @endpush
    @endonce
    <div class="best-selling__container">
        @include('partials.section-heading', ['headingSection' => $section, 'headingTitle' => ($section->data->showcase_heading ?? null) ?: $section->title, 'headingId' => 'all-products-heading-'.$section->id, 'headingUrl' => route('products.index', $categoryId ? ['filter_category' => $categoryId] : []), 'headingPosition' => 'top'])
        @if ($section->data->showcase_filters ?? true)
            <div class="product-section__filters" role="group" aria-label="Filter products by category">
                <button class="product-section__filter" type="button" wire:click="selectCategory(0)" aria-pressed="{{ $categoryId === 0 ? 'true' : 'false' }}" wire:loading.attr="disabled">All</button>
                @foreach ($categories as $category)
                    <button class="product-section__filter" type="button" wire:key="catalog-{{ $section->id }}-category-{{ $category->id }}" wire:click="selectCategory({{ $category->id }})" aria-pressed="{{ $categoryId === $category->id ? 'true' : 'false' }}" wire:loading.attr="disabled">{{ $category->name }}</button>
                @endforeach
            </div>
        @endif
        <div class="product-section__grid" wire:loading.class="opacity-50" aria-live="polite">
            @forelse ($products as $product)
                <div wire:key="catalog-{{ $section->id }}-product-{{ $product->id }}">@include('partials.products.showcase-card', ['product' => $product])</div>
            @empty
                <p class="product-section__status">No products found in this category.</p>
            @endforelse
        </div>
        <div class="product-section__footer">
            @if ($hasMore && $visibleCount < 240)
                <button class="product-section__pill" wire:click="loadMore" wire:loading.attr="disabled" type="button"><span wire:loading.remove wire:target="loadMore">Load More</span><span wire:loading wire:target="loadMore">Loading products…</span></button>
            @endif
        </div>
        @include('partials.section-heading', ['headingSection' => $section, 'headingTitle' => ($section->data->showcase_heading ?? null) ?: $section->title, 'headingId' => 'all-products-heading-'.$section->id, 'headingUrl' => route('products.index', $categoryId ? ['filter_category' => $categoryId] : []), 'headingPosition' => 'bottom'])
        <div class="product-section__status" wire:loading role="status">Updating products…</div>
    </div>
</section>
