<svg class="appearance-preview" viewBox="0 0 300 120" role="img" aria-label="{{ ucfirst($previewVariant).' '.$previewPart }} layout preview" xmlns="http://www.w3.org/2000/svg">
    @if ($previewPart === 'header')
        <rect x="{{ $previewVariant === 'floating' ? 10 : 0 }}" y="{{ $previewVariant === 'floating' ? 12 : 0 }}" width="{{ $previewVariant === 'floating' ? 280 : 300 }}" height="74" rx="{{ $previewVariant === 'floating' ? 12 : 0 }}" fill="#234c42"/>
        @if ($previewVariant === 'studio')
            <rect x="122" y="22" width="56" height="13" rx="3" fill="#fff"/>
            <rect x="14" y="20" width="88" height="19" rx="10" fill="#e8f0ec"/>
            <path d="M211 25h12m9 0h12m9 0h12" stroke="#fff" stroke-width="6" stroke-linecap="round"/>
        @else
            <rect x="22" y="{{ $previewVariant === 'floating' ? 29 : 19 }}" width="48" height="12" rx="3" fill="#fff"/>
            <rect x="86" y="{{ $previewVariant === 'floating' ? 25 : 15 }}" width="114" height="22" rx="6" fill="#f8faf8"/>
            <path d="M222 {{ $previewVariant === 'floating' ? 35 : 25 }}h8m9 0h8m9 0h8" stroke="#fff" stroke-width="6" stroke-linecap="round"/>
        @endif
        <path d="M32 60h30m14 0h30m14 0h30m14 0h30m14 0h30m14 0h22" stroke="#c8ddd5" stroke-width="4" stroke-linecap="round"/>
        <rect x="18" y="96" width="170" height="7" rx="3" fill="#dce4df"/>
        <rect x="204" y="96" width="78" height="7" rx="3" fill="#e2d3b6"/>
    @else
        <rect width="300" height="120" rx="{{ $previewVariant === 'floating' ? 14 : 0 }}" fill="#234c42"/>
        @if ($previewVariant === 'studio')
            <rect x="105" y="12" width="90" height="10" rx="3" fill="#fff"/>
            <rect x="80" y="30" width="140" height="4" rx="2" fill="#a7c4b8"/>
            <path d="M24 47h252" stroke="#56766b"/>
            @foreach ([30, 120, 210] as $previewX)
                <rect x="{{ $previewX }}" y="59" width="58" height="5" rx="2" fill="#fff"/>
                <path d="M{{ $previewX }} 75h52m-52 10h42m-42 10h48" stroke="#a7c4b8" stroke-width="3" stroke-linecap="round"/>
            @endforeach
        @else
            <rect x="12" y="10" width="276" height="29" rx="{{ $previewVariant === 'floating' ? 8 : 0 }}" fill="#345d51"/>
            <rect x="22" y="20" width="122" height="7" rx="2" fill="#fff"/>
            <rect x="224" y="17" width="52" height="14" rx="5" fill="#e8f0ec"/>
            @foreach ([14, 88, 162, 236] as $previewX)
                @if ($previewVariant === 'floating')<rect x="{{ $previewX }}" y="48" width="62" height="54" rx="7" fill="none" stroke="#638274"/>@endif
                <rect x="{{ $previewX + 6 }}" y="57" width="40" height="5" rx="2" fill="#fff"/>
                <path d="M{{ $previewX + 6 }} 74h40m-40 9h32m-32 9h36" stroke="#a7c4b8" stroke-width="3" stroke-linecap="round"/>
            @endforeach
        @endif
        <path d="M14 111h272" stroke="#56766b"/>
    @endif
</svg>
