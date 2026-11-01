@php
    $cardImage = $product->thumbnail->first()?->src ?? $product->base_image?->src;
    $cardUrl = route('products.show', $product);
    $cardOptions = $show_option ?? setting('show_option');
    $isWholesale = isOninda();
    $resellEnabled = $isWholesale && (app()->bound('app.resell') ? app('app.resell') : config('app.resell'));
    $hideCardPrice = $isWholesale && !($cardOptions->guest_can_see_price ?? false) && (auth('user')->guest() || !auth('user')->user()->is_verified);
    $canPurchase = !$product->should_track || $product->stock_count > 0;
    $hasCardVariations = ($product->variations_count ?? 0) > 1;
    $cardDiscount = $product->price > $product->selling_price && $product->selling_price > 0;
    $cardDiscountPercent = $cardDiscount ? (int) round(($product->price - $product->selling_price) / $product->price * 100) : 0;
    $cardActionMode = $section->data->showcase_actions ?? 'both';
    $cardButtonType = $cardOptions->product_grid_button ?? 'add_to_cart';
    $showCartAction = $cardActionMode === 'both' || $cardButtonType === 'add_to_cart';
    $showBuyAction = $cardActionMode === 'both' || $cardButtonType === 'order_now';
@endphp
<article class="showcase-card" data-id="{{ $product->id }}" data-max="{{ $product->should_track ? $product->stock_count : -1 }}">
    @if ($cardDiscountPercent > 0)
        <span class="showcase-card__badge">Save {{ $cardDiscountPercent }}%</span>
    @endif
    <a class="showcase-card__media" href="{{ $cardUrl }}" wire:navigate.hover aria-label="{{ $product->name }}">
        @if ($cardImage)
            <img src="{{ cdn($cardImage, 600, 600) }}" alt="{{ $product->name }}" width="600" height="600" loading="lazy" decoding="async" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
        @endif
        <span class="showcase-card__placeholder" @if ($cardImage) hidden @endif>
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linejoin="round" aria-hidden="true"><path d="m12 2 9 5v10l-9 5-9-5V7zM3 7l9 5 9-5M12 12v10M7.5 4.5l9 5"/></svg>
            <span>{{ $product->name }}</span>
        </span>
    </a>
    <div class="showcase-card__body">
        <h3 class="showcase-card__name"><a href="{{ $cardUrl }}" wire:navigate.hover data-name="{{ $product->name }}">{{ $product->name }}</a></h3>
        <div class="showcase-card__pricing">
            @if ($resellEnabled)
                <span class="showcase-card__retail">Retail: {!! theMoney($product->retailPrice()) !!}</span>
            @endif
            @if ($hideCardPrice || ($resellEnabled && auth('user')->guest()))
                <a class="showcase-card__price-note" href="{{ route('user.login') }}">{{ auth('user')->guest() ? 'Sign in to see price' : 'Verify account to see price' }}</a>
            @elseif ($product->selling_price > 0 || $product->price > 0)
                <strong class="showcase-card__price">{!! theMoney($cardDiscount ? $product->selling_price : ($product->selling_price ?: $product->price)) !!}</strong>
                @if ($cardDiscount)<del class="showcase-card__old-price">{!! theMoney($product->price) !!}</del>@endif
            @else
                <span class="showcase-card__price-note">Contact for price</span>
            @endif
        </div>
        @if (!$canPurchase)
            <span class="showcase-card__unavailable">Out of stock</span>
        @elseif (!$isWholesale && ($showCartAction || $showBuyAction))
            <div class="showcase-card__buttons">
                @foreach (['add' => $showCartAction, 'kart' => $showBuyAction] as $action => $showAction)
                    @if ($showAction)
                        @php
                            $isBuyAction = $action === 'kart';
                            $actionLabel = $isBuyAction ? (($cardOptions->order_now_text ?? null) ?: 'Buy now') : (($cardOptions->add_to_cart_text ?? null) ?: 'Add to cart');
                        @endphp
                        @if ($hasCardVariations)
                            <a class="showcase-card__button {{ $isBuyAction ? 'showcase-card__button--buy' : 'showcase-card__button--cart' }}" href="{{ $cardUrl }}" wire:navigate.hover aria-label="{{ $actionLabel }} — {{ $product->name }}">
                        @else
                            <button class="showcase-card__button {{ $isBuyAction ? 'showcase-card__button--buy' : 'showcase-card__button--cart' }}" type="button" data-product-id="{{ $product->id }}" data-action="{{ $action }}" onclick="handleAddToCart(this)" aria-label="{{ $actionLabel }} — {{ $product->name }}">
                        @endif
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h3l3 13h11l2-10H6"/><circle cx="9" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>
                            <span>{{ $actionLabel }}</span>
                        @if ($hasCardVariations)</a>@else</button>@endif
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</article>
