@if (($show_option->category_carousel ?? false) && ($featuredCategories = categories())->isNotEmpty())
    @push('scripts')
        <script src="{{ versionedAsset('strokya/js/featured-categories.js') }}" defer></script>
    @endpush
    <section class="featured-categories" aria-labelledby="featured-categories-title" data-category-slider>
        <div class="storefront-hero__container">
            <h2 id="featured-categories-title">Featured Categories</h2>
            <div class="featured-categories__slider">
                <button class="featured-categories__arrow featured-categories__arrow--previous" type="button" aria-label="Previous categories" aria-controls="featured-categories-list" data-category-previous hidden disabled>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"/></svg>
                </button>
                <div id="featured-categories-list" class="featured-categories__list" role="group" aria-label="Product categories" data-category-track>
                    @foreach ($featuredCategories as $featuredCategory)
                        <a class="featured-categories__item" href="{{ route('categories.products', $featuredCategory->slug) }}" wire:navigate.hover>
                            <span class="featured-categories__image">
                                @if (!empty($featuredCategory->image_id))
                                    <img src="{{ cdn($featuredCategory->image_src, 240, 240) }}" alt="" width="160" height="160" loading="lazy" decoding="async">
                                @else
                                    <span class="featured-categories__initial" aria-hidden="true">{{ \Illuminate\Support\Str::substr($featuredCategory->name, 0, 1) }}</span>
                                @endif
                            </span>
                            <span>{{ $featuredCategory->name }}</span>
                        </a>
                    @endforeach
                </div>
                <button class="featured-categories__arrow featured-categories__arrow--next" type="button" aria-label="Next categories" aria-controls="featured-categories-list" data-category-next hidden>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </section>
@endif
