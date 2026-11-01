<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch ($icon)
        @case('phone')<path d="m6 3 3 5-3 3a15 15 0 0 0 7 7l3-3 5 3c0 3-2 4-4 4C9 21 3 15 2 7c0-2 1-4 4-4Z"/>@break
        @case('mail')<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/>@break
        @case('pin')<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>@break
        @case('clock')<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>@break
        @case('up')<path d="M12 20V4m-6 6 6-6 6 6"/>@break
        @default <path d="M4 12h16m-6-6 6 6-6 6"/>
    @endswitch
</svg>
