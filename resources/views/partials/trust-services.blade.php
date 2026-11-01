@php
    $visibleServices = $serviceItems->filter(fn ($item) => !empty($item['enabled']) && filled($item['title']))->values();
    $trustColumns = min(max(2, (int) $serviceSettings['columns']), max(1, $visibleServices->count()));
@endphp
@if ($serviceSettings['enabled'] && $visibleServices->isNotEmpty())
    @push('styles')
        <style>
            .store-trust { padding: 4px 0 28px; background: var(--trust-background); }
            .store-trust__container { max-width: 1536px; margin: 0 auto; padding: 0 clamp(20px, 3vw, 48px); }
            .store-trust__heading { margin: 0 0 22px; text-align: center; color: var(--trust-text); font-size: clamp(20px, 2vw, 28px); font-weight: 500; }
            .store-trust__grid { display: grid; grid-template-columns: repeat(var(--trust-columns), minmax(0, 1fr)); gap: 1px; overflow: hidden; border: 1px solid var(--trust-border); border-radius: 20px; background: var(--trust-border); }
            .store-trust__item { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; min-width: 0; min-height: 140px; padding: 28px 24px; text-align: center; background: var(--trust-card); color: var(--trust-text); text-decoration: none; }
            .store-trust__icon { display: flex; color: var(--trust-icon); }
            .store-trust__title { font-size: 16px; font-weight: 600; line-height: 1.5; overflow-wrap: anywhere; }
            .store-trust__detail { max-width: 340px; margin: -4px 0 0; font-size: 13px; line-height: 1.65; opacity: .78; }
            a.store-trust__item:hover { color: var(--trust-text); text-decoration: none; }
            a.store-trust__item:hover .store-trust__title { text-decoration: underline; text-underline-offset: 5px; }
            a.store-trust__item:focus-visible { outline: 2px solid var(--trust-icon); outline-offset: -5px; border-radius: 6px; }
            @media (max-width: 767px) {
                .store-trust__container { padding: 0 16px; }
                .store-trust__item { padding: 24px 16px; min-height: 128px; }
                .store-trust__title { font-size: 14px; }
                .store-trust__grid { grid-template-columns: repeat(var(--trust-mobile-columns), minmax(0, 1fr)); border-radius: 16px; }
            }
            @media (max-width: 479px) {
                .store-trust__grid { grid-template-columns: minmax(0, 1fr); }
                .store-trust__item { min-height: 112px; padding: 22px 20px; gap: 10px; }
            }
        </style>
    @endpush
    <section class="store-trust" aria-label="{{ $serviceSettings['heading'] ?: 'Why shop with us' }}" style="--trust-columns: {{ $trustColumns }}; --trust-mobile-columns: {{ min(2, $visibleServices->count()) }}; --trust-background: {{ $serviceSettings['background_color'] }}; --trust-card: {{ $serviceSettings['card_color'] }}; --trust-text: {{ $serviceSettings['text_color'] }}; --trust-icon: {{ $serviceSettings['icon_color'] }}; --trust-border: {{ $serviceSettings['border_color'] }};">
        <div class="store-trust__container">
            @if (filled($serviceSettings['heading']))<h2 class="store-trust__heading">{{ $serviceSettings['heading'] }}</h2>@endif
            <div class="store-trust__grid">
                @foreach ($visibleServices as $service)
                    @if (filled($service['link']))
                        <a class="store-trust__item" href="{{ $service['link'] }}">
                    @else
                        <div class="store-trust__item">
                    @endif
                        <span class="store-trust__icon">@include('partials.trust-icon', ['icon' => $service['icon']])</span>
                        <span class="store-trust__title">{{ $service['title'] }}</span>
                        @if ($serviceSettings['show_details'] && filled($service['detail']))<p class="store-trust__detail">{{ $service['detail'] }}</p>@endif
                    @if (filled($service['link']))</a>@else</div>@endif
                @endforeach
            </div>
        </div>
    </section>
@endif
