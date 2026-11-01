<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($icon)
        @case('flask')
            <path d="M9 2h6M10 2v7L5 21h14L14 9V2M8 14h8"/>
            @break
        @case('truck')
            <path d="M2 4h14v14H2zM16 9h4l3 4v5h-7"/><circle cx="6" cy="20" r="2"/><circle cx="19" cy="20" r="2"/>
            @break
        @case('return')
            <path d="M20 3v6h-6M4 21v-6h6M20 9a8 8 0 0 0-14-5M4 15a8 8 0 0 0 14 5"/>
            @break
        @case('headphones')
            <path d="M3 14v-3a9 9 0 0 1 18 0v3M3 13h4v8H3zM17 13h4v8h-4z"/>
            @break
        @case('lock')
            <rect x="4" y="10" width="16" height="12" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4M12 15v3"/>
            @break
        @case('leaf')
            <path d="M20 3c-2 4-12-2-16 7a7 7 0 0 0 11 8c5-4 5-11 5-15ZM3 22 15 10"/>
            @break
        @case('package')
            <path d="m12 2 9 5v10l-9 5-9-5V7zM3 7l9 5 9-5M12 12v10M7.5 4.5l9 5"/>
            @break
        @case('star')
            <path d="m12 2 3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/>
            @break
        @default
            <path d="m12 2 8 4v6c0 5-8 10-8 10S4 17 4 12V6zM8 12l3 3 5-6"/>
    @endswitch
</svg>
