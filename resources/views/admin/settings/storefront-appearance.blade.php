<fieldset class="card mb-4 storefront-appearance">
    <div class="card-header p-3">
        <h4 class="mb-1">Header & Footer Design</h4>
        <p class="text-muted mb-0">Choose each independently, or pair matching styles. Your logos, menus, colors and contact details work with every design. Save settings to apply.</p>
    </div>
    <div class="card-body p-3">
        @foreach (['header' => 'Header', 'footer' => 'Footer'] as $part => $partLabel)
            <fieldset class="mb-3">
                <legend class="h5">{{ $partLabel }} style</legend>
                <div class="appearance-options">
                    @foreach (config('storefront.'.$part.'_variants') as $variant => $option)
                        <label class="appearance-choice">
                            <input type="radio" name="company[{{ $part }}_variant]" value="{{ $variant }}" @checked(old('company.'.$part.'_variant', $company->{$part.'_variant'} ?? 'signature') === $variant)>
                            <span class="appearance-choice__body">
                                @include('admin.settings.appearance-preview', ['previewPart' => $part, 'previewVariant' => $variant])
                                <strong>{{ $option['name'] }}</strong>
                                <small>{{ $option['description'] }}</small>
                                <span class="appearance-choice__selected">Selected</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                <x-error :field="'company.'.$part.'_variant'" />
            </fieldset>
        @endforeach
        <p class="text-muted mb-0"><small>Change colors in Color Settings → Header, Navbar, Search and Footer. All designs adapt to mobile and tablet screens.</small></p>
    </div>
</fieldset>
<style>
    .appearance-options { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
    .appearance-choice { display: block; margin: 0; position: relative; cursor: pointer; min-width: 0; }
    .appearance-choice > input { position: absolute; top: 14px; right: 14px; z-index: 1; width: 17px; height: 17px; accent-color: #536dfe; }
    .appearance-choice__body { display: flex; flex-direction: column; gap: 8px; height: 100%; padding: 12px; border: 2px solid #e4e8ef; border-radius: 12px; background: #fff; color: #263445; transition: border-color .15s ease, box-shadow .15s ease; }
    .appearance-choice:hover .appearance-choice__body { border-color: #9daed8; }
    .appearance-choice > input:checked + .appearance-choice__body { border-color: #536dfe; box-shadow: 0 0 0 2px #536dfe12; }
    .appearance-choice > input:focus-visible + .appearance-choice__body { outline: 2px solid #263445; outline-offset: 3px; }
    .appearance-choice small { color: #617084; font-size: 12px; line-height: 1.6; }
    .appearance-choice__selected { font-size: 11px; font-weight: 600; color: #4057d6; visibility: hidden; margin-top: auto; }
    .appearance-choice > input:checked + .appearance-choice__body .appearance-choice__selected { visibility: visible; }
    .appearance-preview { display: block; width: 100%; height: 115px; border-radius: 7px; background: #f3f5f8; }
    @media (max-width: 767px) { .appearance-options { grid-template-columns: 1fr; } .appearance-preview { height: 130px; } }
    @media (prefers-reduced-motion: reduce) { .appearance-choice__body { transition: none; } }
</style>
