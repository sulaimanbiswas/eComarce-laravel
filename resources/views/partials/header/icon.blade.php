<svg class="storefront-header__icon storefront-header__icon--{{ $icon }}" width="22" height="22" viewBox="{{ $icon === 'search' ? '0 0 512 512' : '0 0 24 24' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($icon)
        @case('search')
            <path fill="currentColor" stroke="none" d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
            @break
        @case('track')
            <path d="M3.5 9.5 10 6l6.5 3.5v8L10 21l-6.5-3.5zM3.5 9.5 10 13l6.5-3.5M10 13v8M6.75 7.75l6.5 3.5"/>
            <path d="M21 6c0 2.2-3 4.5-3 4.5S15 8.2 15 6a3 3 0 1 1 6 0Z" fill="var(--store-header-bg)"/><circle cx="18" cy="6" r=".8" fill="currentColor" stroke="none"/>
            @break
        @case('account')
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            @break
        @case('cart')
            <path fill="currentColor" stroke="none" d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49A.996.996 0 0 0 20.01 4H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45ZM6.16 6h12.15l-2.76 5H8.53L6.16 6ZM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2Zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2Z"/>
            @break
        @case('chevron')
            <path d="m6 9 6 6 6-6"/>
            @break
        @default
            <path d="M4 6h16M4 12h16m-7 6h7"/>
    @endswitch
</svg>
