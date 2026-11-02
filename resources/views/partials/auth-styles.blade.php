@once
    @push('styles')
        @php
            $authColorSettings = setting('color');
            $authBrandColor = data_get($authColorSettings, 'header.background_color') ?? '#1c4435';
            $authAccentColor = data_get($authColorSettings, 'badge.background_color') ?? '#d6992c';
            $authPaletteService = app(\App\Services\BrandPalette::class);
        @endphp
        <style>
            .store-auth {
                --auth-brand: {{ $authBrandColor }};
                --auth-brand-text: {{ data_get($authColorSettings, 'header.text_color') ?? '#ffffff' }};
                --auth-accent: {{ $authAccentColor }};
                --auth-link: {{ $authPaletteService->mix($authBrandColor, '#000000', 0.6) }};
                padding: clamp(28px, 5vw, 72px) 20px;
                background: {{ $authPaletteService->mix($authBrandColor, '#ffffff', 0.96) }};
                color: #25382d;
            }
            .store-auth *, .store-auth *::before, .store-auth *::after { box-sizing: border-box; }
            .store-auth__wrap { max-width: 1080px; margin: 0 auto; }
            .store-auth__back { display: inline-flex; align-items: center; gap: 8px; color: #536359; font-size: 13px; text-decoration: none; margin-bottom: 22px; }
            .store-auth__card { display: grid; grid-template-columns: .85fr 1.15fr; border: 1px solid #dfe5dc; border-radius: 20px; overflow: hidden; background: #fff; box-shadow: 0 16px 48px #14291d0a; }
            .store-auth__story { position: relative; display: flex; flex-direction: column; justify-content: space-between; gap: 44px; padding: clamp(28px, 4vw, 48px); background: var(--auth-brand); color: var(--auth-brand-text); overflow: hidden; }
            .store-auth__story::after { content: ''; position: absolute; width: 300px; height: 300px; border: 1px solid #ffffff18; border-radius: 50%; right: -160px; bottom: -90px; pointer-events: none; }
            .store-auth__brand { color: inherit; font-size: 22px; font-weight: 700; overflow-wrap: anywhere; }
            .store-auth__eyebrow { display: block; font-size: 11px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; margin-bottom: 16px; }
            .store-auth__story .store-auth__eyebrow { color: inherit; opacity: .8; }
            .store-auth__story h2 { color: inherit; font-size: clamp(28px, 3vw, 38px); line-height: 1.25; font-weight: 600; margin: 0 0 18px; }
            .store-auth__story p { color: inherit; opacity: .8; font-size: 14px; line-height: 1.8; margin: 0; }
            .store-auth__benefits { display: grid; gap: 16px; padding: 0; margin: 0; list-style: none; font-size: 13px; }
            .store-auth__benefits li { display: flex; align-items: center; gap: 12px; }
            .store-auth__benefits li::before { content: '\2713'; display: grid; place-items: center; width: 26px; height: 26px; flex-shrink: 0; background: var(--auth-accent); border: 1px solid #ffffff26; border-radius: 50%; color: {{ $authPaletteService->foreground($authAccentColor) }}; }
            .store-auth__panel { min-width: 0; padding: clamp(24px, 4vw, 48px); }
            .store-auth__panel h1, .store-auth__panel h2 { color: #25382d; font-size: clamp(25px, 3vw, 30px); font-weight: 600; line-height: 1.3; margin: 0 0 10px; }
            .store-auth__intro { color: #748076; font-size: 14px; line-height: 1.7; margin: 0 0 28px; }
            .store-auth__panel .store-auth__eyebrow { color: #6d796f; margin-bottom: 10px; }
            .store-auth__form { display: grid; gap: 20px; }
            .store-auth__fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
            .store-auth__field { min-width: 0; }
            .store-auth__field--wide { grid-column: 1 / -1; }
            .store-auth label { display: block; color: #34473a; font-size: 13px; font-weight: 600; margin: 0 0 8px; }
            .store-auth input:not([type=checkbox]) { display: block; width: 100%; min-width: 0; height: 48px; border: 1px solid #dce3da; border-radius: 8px; padding: 12px 14px; background: #fafbf8; color: #25382d; font: inherit; font-size: 14px; box-shadow: none; }
            .store-auth input::placeholder { color: #879187 !important; opacity: 1; }
            .store-auth input:focus { border-color: var(--auth-brand); outline: 2px solid var(--auth-brand); outline-offset: 2px; background: #fff; }
            .store-auth input.is-invalid { border-color: #bc3d3d; }
            .store-auth .invalid-feedback { display: block; color: #bc3d3d; font-size: 12px; margin-top: 7px; }
            .store-auth__hint { display: block; color: #748076; font-size: 12px; line-height: 1.6; margin-top: 7px; }
            .store-auth__options { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; font-size: 12px; }
            .store-auth .store-auth__remember { display: flex; align-items: center; gap: 8px; font-weight: 400; margin: 0; cursor: pointer; }
            .store-auth input[type=checkbox] { width: 16px; height: 16px; margin: 0; accent-color: var(--auth-brand); }
            .store-auth__link { color: var(--auth-link); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
            .store-auth__submit { display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; min-height: 48px; padding: 12px 20px; border: 0; border-radius: 8px; background: var(--auth-brand); color: var(--auth-brand-text); font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; transition: box-shadow .2s ease; }
            .store-auth__submit:hover { box-shadow: 0 5px 16px #14291d26; }
            .store-auth a:focus-visible, .store-auth button:focus-visible { outline: 2px solid var(--auth-accent); outline-offset: 4px; }
            .store-auth__switch { border-top: 1px solid #e8ece5; padding-top: 22px; margin: 24px 0 0; text-align: center; color: #748076; font-size: 13px; line-height: 1.8; }
            .store-auth__notice { padding: 12px 14px; border: 1px solid #d5e5d7; border-radius: 8px; background: #f0f7ef; color: #2d5b37; font-size: 13px; margin-bottom: 20px; }
            .store-auth__notice--error { background: #fff4f3; border-color: #f1d4d0; color: #a13535; }
            .store-auth__notice ul { padding-left: 18px; margin: 0; }
            .store-auth--inline { padding-top: 28px; padding-bottom: 28px; }
            .store-auth--inline .store-auth__wrap { max-width: 1200px; }
            .store-auth--inline .store-auth__card { grid-template-columns: .85fr 1.15fr; }
            .store-auth--inline .store-auth__panel + .store-auth__panel { border-left: 1px solid #e8ece5; background: #fcfdf9; }
            @media (max-width: 767px) {
                .store-auth__card, .store-auth--inline .store-auth__card { grid-template-columns: 1fr; }
                .store-auth__story { gap: 24px; }
                .store-auth__benefits { grid-template-columns: 1fr; gap: 10px; }
                .store-auth--inline .store-auth__panel + .store-auth__panel { border-left: 0; border-top: 1px solid #e8ece5; }
            }
            @media (max-width: 479px) {
                .store-auth { padding-left: 12px; padding-right: 12px; }
                .store-auth__fields { grid-template-columns: 1fr; }
                .store-auth__panel { padding: 24px 20px; }
            }
        </style>
    @endpush
@endonce
