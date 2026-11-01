<a href="{{ route('products.index') }}" wire:navigate.hover>Shop All Products</a>
@if ($show_option->customer_login ?? false)
    <a class="storefront-header__mobile-account" href="{{ $accountUrl }}" wire:navigate.hover>{{ $accountIsSignedIn ? 'My Account' : 'Sign In' }}</a>
@endif
<details class="storefront-header__mobile-categories">
    <summary>Categories @include('partials.header.icon', ['icon' => 'chevron'])</summary>
    <ul>@include('partials.header.category-children', ['headerCategories' => $categories])</ul>
</details>
@include('partials.header.menu.desktop')
@foreach ($extraMenuItems as $item)
    @php
        $isExternal = \Illuminate\Support\Str::startsWith($item->href, ['http://', 'https://', 'mailto:', 'tel:', '#']);
    @endphp
    <a href="{{ $isExternal ? $item->href : url($item->href) }}" @unless ($isExternal) wire:navigate.hover @endunless>{{ $item->name }}</a>
@endforeach
@if (!empty($company->phone))
    <a class="storefront-header__help" href="tel:{{ $company->phone }}"><small>Help Line</small>{{ $company->phone }}</a>
@endif
@if (!blank($scroll_text ?? null))
    <div class="storefront-header__announcement">{!! $scroll_text !!}</div>
@endif
