@php
    $paletteService = app(\App\Services\BrandPalette::class);
    $storedPalette = (array) setting('brand_palette');
    $legacyColors = setting('color');
    $paletteDefaults = config('storefront.brand_defaults');
    $legacyPalette = [
        'primary' => data_get($legacyColors, 'header.background_color', $paletteDefaults['primary']),
        'secondary' => data_get($legacyColors, 'add_to_cart.background_color', $paletteDefaults['secondary']),
        'accent' => data_get($legacyColors, 'order_now.background_color', $paletteDefaults['accent']),
    ];
    $initialPalette = [];
    $paletteInputs = [];
    foreach ($paletteDefaults as $role => $defaultColor) {
        $candidate = old('brand_palette.'.$role, $storedPalette[$role] ?? $legacyPalette[$role]);
        $paletteInputs[$role] = is_string($candidate) ? $candidate : '';
        $initialPalette[$role] = is_string($candidate) && preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $candidate)
            ? $paletteService->normalize($candidate) : $defaultColor;
    }
@endphp

<div class="tab-pane active brand-colors" id="item-color" role="tabpanel" data-brand-colors>
    <header class="brand-colors__heading">
        <h3><i class="fa fa-palette" aria-hidden="true"></i> Brand Colors</h3>
        <p>Define your brand color palette.</p>
    </header>
    <fieldset class="brand-colors__presets">
        <legend>Presets</legend>
        <div class="brand-colors__grid">
            @foreach (config('storefront.brand_presets') as $preset => $palette)
                <button type="button" class="brand-colors__preset" data-brand-preset="{{ $preset }}" aria-pressed="false" title="{{ $palette['name'] }}">
                    <span class="brand-colors__dots" aria-hidden="true">
                        @foreach (['primary', 'secondary', 'accent'] as $role)
                            <span style="background: {{ $palette[$role] }}"></span>
                        @endforeach
                    </span>
                    <span class="brand-colors__name">{{ $palette['name'] }}</span>
                </button>
            @endforeach
        </div>
    </fieldset>
    <div class="brand-colors__inputs">
        @foreach (['primary' => 'Primary', 'secondary' => 'Secondary', 'accent' => 'Accent'] as $role => $label)
            <div class="brand-colors__field">
                <label for="brand-{{ $role }}">{{ $label }}</label>
                <div class="brand-colors__control">
                    <input type="color" value="{{ $initialPalette[$role] }}" data-brand-picker="{{ $role }}" aria-label="Choose {{ strtolower($label) }} color">
                    <input id="brand-{{ $role }}" type="text" name="brand_palette[{{ $role }}]"
                        value="{{ $paletteInputs[$role] }}" data-brand-hex="{{ $role }}"
                        pattern="#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})" maxlength="7" required spellcheck="false" autocomplete="off"
                        aria-describedby="brand-color-help @error('brand_palette.'.$role) brand-{{ $role }}-error @enderror"
                        @error('brand_palette.'.$role) aria-invalid="true" @enderror>
                </div>
                @error('brand_palette.'.$role)
                    <span class="brand-colors__error" id="brand-{{ $role }}-error" role="alert">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>
    <p class="brand-colors__help" id="brand-color-help">Pick a preset or enter your own hex colors. Save settings to apply across your storefront.</p>
    <div class="brand-colors__preview" data-brand-preview>
        <div class="brand-colors__preview-label"><span>Preview</span><span data-brand-selection aria-live="polite">Custom palette</span></div>
        <div class="brand-colors__swatches" aria-hidden="true">
            @foreach (['primary', 'secondary', 'accent'] as $role)
                <span data-brand-swatch="{{ $role }}" style="background: {{ $initialPalette[$role] }}"></span>
            @endforeach
        </div>
        <div class="brand-colors__store">
            <div class="brand-colors__store-header"><strong>{{ setting('company')->name ?? config('app.name') }}</strong><span>Shop &nbsp; / &nbsp; Collections</span></div>
            <div class="brand-colors__store-body">
                <small>YOUR BRAND, YOUR STYLE</small><h4>A fresh look for your store.</h4><p>A coordinated palette from your storefront to your account pages.</p>
                <div class="brand-colors__sample-actions"><span class="brand-colors__sample-primary">Explore collection &rarr;</span><span class="brand-colors__sample-secondary">Add to cart</span><span class="brand-colors__sample-accent">New arrival</span></div>
            </div>
            <div class="brand-colors__store-footer"><strong>Your store, beautifully connected.</strong><span>About &nbsp; / &nbsp; Contact &nbsp; / &nbsp; Support</span></div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .brand-colors { max-width: 1080px; padding: 12px 12px 28px; color: #172033; }
        .brand-colors *, .brand-colors *::before, .brand-colors *::after { box-sizing: border-box; }
        .brand-colors__heading { margin-bottom: 30px; }
        .brand-colors__heading h3 { display: flex; align-items: center; gap: 10px; font-size: 24px; font-weight: 600; margin: 0 0 8px; }
        .brand-colors__heading p { color: #758297; margin: 0; font-size: 14px; }
        .brand-colors__presets { min-width: 0; padding: 0; margin: 0 0 26px; border: 0; }
        .brand-colors__presets legend { float: none; width: auto; padding: 0; margin: 0 0 12px; font-size: 14px; font-weight: 500; }
        .brand-colors__grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
        .brand-colors__preset { display: flex; align-items: center; gap: 12px; min-width: 0; padding: 11px 12px; min-height: 48px; border: 1px solid #dfe4ec; border-radius: 8px; background: #fff; color: #172033; text-align: left; font: inherit; font-size: 13px; cursor: pointer; transition: border-color .15s ease, background .15s ease; }
        .brand-colors__preset:hover { border-color: #94a3b8; background: #f8fafc; }
        .brand-colors__preset[aria-pressed=true] { border-color: #2563eb; background: #eff6ff; box-shadow: 0 0 0 1px #2563eb; }
        .brand-colors__dots { display: flex; gap: 5px; flex-shrink: 0; }
        .brand-colors__dots span { width: 22px; height: 22px; border-radius: 50%; box-shadow: inset 0 0 0 1px #00000008; }
        .brand-colors__name { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
        .brand-colors__inputs { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
        .brand-colors__field { min-width: 0; }
        .brand-colors__field label { display: block; margin: 0 0 10px; font-size: 14px; font-weight: 500; }
        .brand-colors__control { display: flex; align-items: center; gap: 10px; }
        .brand-colors__control input[type=color] { flex-shrink: 0; width: 38px; height: 44px; padding: 3px; border: 1px solid #dfe4ec; border-radius: 5px; background: #f1f5f9; cursor: pointer; }
        .brand-colors__control input[type=text] { width: 100%; min-width: 0; height: 44px; padding: 10px 12px; border: 1px solid #bdcbea; border-radius: 6px; background: #fff; color: #172033; font: 14px ui-monospace, SFMono-Regular, Consolas, monospace; }
        .brand-colors__control input[type=text]:invalid { border-color: #dc2626; }
        .brand-colors button:focus-visible, .brand-colors input:focus-visible { outline: 2px solid #2563eb; outline-offset: 3px; }
        .brand-colors__help { margin: 12px 0 22px; color: #758297; font-size: 12px; line-height: 1.7; }
        .brand-colors__error { display: block; color: #dc2626; font-size: 12px; padding-top: 6px; }
        .brand-colors__preview { padding: 20px; border: 1px solid #dfe4ec; border-radius: 10px; background: #fff; }
        .brand-colors__preview-label { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 8px; color: #758297; font-size: 12px; margin-bottom: 12px; }
        .brand-colors__swatches { display: flex; gap: 14px; margin-bottom: 22px; }
        .brand-colors__swatches span { width: 60px; height: 60px; border-radius: 8px; }
        .brand-colors__store { overflow: hidden; border: 1px solid #e2e8f0; border-radius: 10px; }
        .brand-colors__store-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 22px; background: var(--preview-primary); color: var(--preview-on-primary); }
        .brand-colors__store-header strong { font-size: 17px; overflow-wrap: anywhere; }
        .brand-colors__store-header span { font-size: 11px; }
        .brand-colors__store-body { padding: 26px 22px; background: var(--preview-soft); color: var(--preview-ink); }
        .brand-colors__store-body small { font-size: 9px; font-weight: 700; letter-spacing: .14em; }
        .brand-colors__store-body h4 { font-size: 23px; color: inherit; margin: 10px 0; }
        .brand-colors__store-body p { font-size: 12px; margin: 0 0 20px; }
        .brand-colors__sample-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
        .brand-colors__sample-actions span { padding: 10px 14px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .brand-colors__sample-primary { background: var(--preview-primary); color: var(--preview-on-primary); }
        .brand-colors__sample-secondary { background: var(--preview-secondary); color: var(--preview-on-secondary); }
        .brand-colors__sample-accent { background: var(--preview-accent); color: var(--preview-on-accent); }
        .brand-colors__store-footer { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; padding: 20px 22px; background: var(--preview-footer); color: #f1f5f9; border-top: 3px solid var(--preview-footer-accent); font-size: 11px; }
        .brand-colors__store-footer span { color: var(--preview-footer-accent); }
        @media (max-width: 991px) { .brand-colors__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 575px) {
            .brand-colors { padding: 4px 0 24px; }
            .brand-colors__grid, .brand-colors__inputs { grid-template-columns: 1fr; }
            .brand-colors__preview { padding: 14px; }
            .brand-colors__store-header, .brand-colors__store-body { padding: 18px 14px; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const root = document.querySelector('[data-brand-colors]');
            if (!root || root.dataset.initialized) { return; }
            root.dataset.initialized = 'true';
            const presets = {{ Js::from(config('storefront.brand_presets')) }};
            const palette = {{ Js::from($initialPalette) }};
            const roles = ['primary', 'secondary', 'accent'];
            const preview = root.querySelector('[data-brand-preview]');
            const normalize = value => {
                const color = value.trim().toLowerCase();
                if (/^#[a-f0-9]{3}$/.test(color)) { return '#' + [...color.slice(1)].map(channel => channel + channel).join(''); }
                return /^#[a-f0-9]{6}$/.test(color) ? color : null;
            };
            const mix = (color, target, weight) => '#' + [1, 3, 5].map(offset => {
                const channel = parseInt(color.slice(offset, offset + 2), 16);
                const other = parseInt(target.slice(offset, offset + 2), 16);
                return Math.round(channel + (other - channel) * weight).toString(16).padStart(2, '0');
            }).join('');
            const foreground = color => {
                const channels = [1, 3, 5].map(offset => {
                    const channel = parseInt(color.slice(offset, offset + 2), 16) / 255;
                    return channel <= 0.04045 ? channel / 12.92 : ((channel + 0.055) / 1.055) ** 2.4;
                });
                const luminance = channels[0] * 0.2126 + channels[1] * 0.7152 + channels[2] * 0.0722;
                return 1.05 / (luminance + 0.05) >= (luminance + 0.05) / 0.05 ? '#ffffff' : '#000000';
            };
            const render = () => {
                roles.forEach(role => {
                    preview.style.setProperty('--preview-' + role, palette[role]);
                    preview.style.setProperty('--preview-on-' + role, foreground(palette[role]));
                    root.querySelector('[data-brand-swatch="' + role + '"]').style.background = palette[role];
                });
                preview.style.setProperty('--preview-soft', mix(palette.primary, '#ffffff', 0.95));
                preview.style.setProperty('--preview-ink', mix(palette.primary, '#000000', 0.6));
                preview.style.setProperty('--preview-footer', mix(palette.primary, '#101827', 0.86));
                preview.style.setProperty('--preview-footer-accent', mix(palette.accent, '#ffffff', 0.65));
                let selected = 'Custom palette';
                root.querySelectorAll('[data-brand-preset]').forEach(button => {
                    const preset = presets[button.dataset.brandPreset];
                    const matches = roles.every(role => normalize(root.querySelector('[data-brand-hex="' + role + '"]').value) === preset[role]);
                    button.setAttribute('aria-pressed', String(matches));
                    if (matches) { selected = preset.name; }
                });
                root.querySelector('[data-brand-selection]').textContent = selected;
            };
            const update = (role, value, syncText) => {
                const input = root.querySelector('[data-brand-hex="' + role + '"]');
                const color = normalize(value);
                input.setCustomValidity(color ? '' : 'Enter a valid hex color, such as #2563eb.');
                input.setAttribute('aria-invalid', String(!color));
                if (color) {
                    palette[role] = color;
                    root.querySelector('[data-brand-picker="' + role + '"]').value = color;
                    if (syncText) { input.value = color; }
                }
                render();
            };
            roles.forEach(role => {
                root.querySelector('[data-brand-hex="' + role + '"]').addEventListener('input', event => update(role, event.target.value, false));
                root.querySelector('[data-brand-picker="' + role + '"]').addEventListener('input', event => update(role, event.target.value, true));
            });
            root.querySelectorAll('[data-brand-preset]').forEach(button => {
                button.addEventListener('click', () => roles.forEach(role => update(role, presets[button.dataset.brandPreset][role], true)));
            });
            render();
        })();
    </script>
@endpush
