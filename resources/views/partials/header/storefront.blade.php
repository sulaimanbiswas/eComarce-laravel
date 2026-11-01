@php
    $accountIsSignedIn = auth('admin')->check() || auth('user')->check();
    $accountUrl = isOninda() ? route('reseller.dashboard') : route('admin.login');
    $headerVariant = array_key_exists($company->header_variant ?? '', config('storefront.header_variants')) ? $company->header_variant : 'signature';
@endphp
<header class="site__header storefront-header storefront-header--{{ $headerVariant }}" aria-label="Store header" data-search-open="{{ request()->filled('search') ? 'true' : 'false' }}">
    <div class="storefront-header__main">
        <div class="storefront-header__inner">
            <a class="storefront-header__logo" href="{{ url('/') }}" wire:navigate.hover aria-label="{{ $company->name ?? config('app.name') }} home">
                @if (!empty($logo->desktop) || !empty($logo->mobile))
                    <picture>
                        <source media="(max-width: 767px)" srcset="{{ asset(($logo->mobile ?? null) ?: $logo->desktop) }}">
                        <img src="{{ asset(($logo->desktop ?? null) ?: $logo->mobile) }}" alt="{{ $company->name ?? config('app.name') }}" height="40">
                    </picture>
                @else
                    <span>{{ $company->name ?? config('app.name') }}<small>{{ $company->tagline ?? '' }}</small></span>
                @endif
            </a>

            <div id="storefront-search-panel" class="site-header__search storefront-header__search">
                <form action="{{ route('products.index') }}" method="get" role="search">
                    <label class="sr-only" for="storefront-search">Search products</label>
                    <input id="storefront-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search in {{ $company->name ?? config('app.name') }}..." autocomplete="off" inputmode="search" enterkeyhint="search" maxlength="100">
                    <button type="submit" aria-label="Search products">@include('partials.header.icon', ['icon' => 'search'])</button>
                </form>
                <div id="desktop-search-suggestions" class="search-suggestions-dropdown storefront-header__suggestions" style="display:none;"></div>
            </div>

            <div class="storefront-header__actions">
                @if ($show_option->track_order ?? false)
                    <a class="storefront-header__action" href="{{ route('track-order') }}" wire:navigate.hover aria-label="Order Track">
                        @include('partials.header.icon', ['icon' => 'track'])<span>Order Track</span>
                    </a>
                @endif
                <button class="storefront-header__action storefront-header__search-toggle" type="button" data-storefront-search-toggle aria-label="Open search" aria-controls="storefront-search-panel" aria-expanded="{{ request()->filled('search') ? 'true' : 'false' }}">
                    @include('partials.header.icon', ['icon' => 'search'])
                </button>
                @if ($show_option->customer_login ?? false)
                    <a class="storefront-header__action storefront-header__account" href="{{ $accountUrl }}" wire:navigate.hover>
                        @include('partials.header.icon', ['icon' => 'account'])<span>{{ $accountIsSignedIn ? 'My Account' : 'Sign In' }}</span>
                    </a>
                @endif
                <details class="storefront-header__cart storefront-header__disclosure">
                    <summary class="storefront-header__action" aria-label="Shopping cart">
                        <span class="storefront-header__cart-icon">@include('partials.header.icon', ['icon' => 'cart'])<livewire:cart-count /></span><span>Cart</span>
                    </summary>
                    <div class="storefront-header__dropdown storefront-header__cart-panel"><livewire:cart-box /></div>
                </details>
                <details class="storefront-header__more storefront-header__disclosure">
                    <summary class="storefront-header__action" aria-label="More menu">@include('partials.header.icon', ['icon' => 'menu'])<span>More</span></summary>
                    <div class="storefront-header__dropdown storefront-header__more-panel">@include('partials.header.more')</div>
                </details>
            </div>
        </div>
    </div>
    <nav class="storefront-header__navigation" aria-label="Product categories">
        <ul class="storefront-header__categories">
            @forelse ($categories as $headerCategory)
                <li>
                    @if ($headerCategory->childrens->where('is_enabled', true)->isNotEmpty())
                        <details class="storefront-header__category storefront-header__disclosure">
                            <summary><a href="{{ route('categories.products', $headerCategory->slug) }}" wire:navigate.hover>{{ $headerCategory->name }}</a>@include('partials.header.icon', ['icon' => 'chevron'])</summary>
                            <ul class="storefront-header__dropdown storefront-header__children">@include('partials.header.category-children', ['headerCategories' => $headerCategory->childrens])</ul>
                        </details>
                    @else
                        <a href="{{ route('categories.products', $headerCategory->slug) }}" wire:navigate.hover>{{ $headerCategory->name }}</a>
                    @endif
                </li>
            @empty
                <li><a href="{{ route('products.index') }}" wire:navigate.hover>Shop All Products</a></li>
            @endforelse
        </ul>
    </nav>
</header>
