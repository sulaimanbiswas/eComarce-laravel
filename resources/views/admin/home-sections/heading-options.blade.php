@php $defaultHeadingOptions = array_replace(config('storefront.heading_defaults'), (array) setting('section_heading')); @endphp
<div class="col-12"><fieldset class="border rounded p-3 mb-3"><legend class="w-auto px-2" style="font-size: 15px;">Title & View All Style</legend>
    <p class="small text-muted">Choose “Use global settings” to keep every section consistent. Global styles and colors are in Settings → Others → Section Titles & View All.</p>
    <div class="section-template-picker mb-3">
        <label class="section-template-option"><input type="radio" name="data[heading_variant]" value="inherit" @checked(old('data.heading_variant', $section->data->heading_variant ?? 'inherit') === 'inherit')><span class="section-template-option__body"><img src="{{ asset('assets/images/layouts/heading-'.$defaultHeadingOptions['variant'].'.svg') }}" width="240" height="100" alt="Global heading layout"><strong>Use global settings</strong><small>Match all other sections</small></span></label>
        @foreach (config('storefront.heading_variants') as $key => $label)
            <label class="section-template-option"><input type="radio" name="data[heading_variant]" value="{{ $key }}" @checked(old('data.heading_variant', $section->data->heading_variant ?? 'inherit') === $key)><span class="section-template-option__body"><img src="{{ asset('assets/images/layouts/heading-'.$key.'.svg') }}" width="240" height="100" alt="{{ $label }}"><strong>{{ $label }}</strong></span></label>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-6 form-group"><label for="heading-palette">Colors</label><select class="form-control" id="heading-palette" name="data[heading_palette]"><option value="inherit" @selected(old('data.heading_palette', $section->data->heading_palette ?? 'inherit') === 'inherit')>Use global heading colors</option><option value="custom" @selected(old('data.heading_palette', $section->data->heading_palette ?? 'inherit') === 'custom')>Use this section's custom colors below</option></select></div>
        <div class="col-md-6 form-group"><label for="heading-link-text">View All label (optional)</label><input class="form-control" id="heading-link-text" name="data[heading_link_text]" maxlength="40" value="{{ old('data.heading_link_text', $section->data->heading_link_text ?? '') }}" placeholder="{{ $defaultHeadingOptions['link_text'] }}"></div>
        @foreach (['title_color' => 'Title', 'link_color' => 'View All text', 'link_hover' => 'View All hover', 'accent_color' => 'Underline / accent', 'link_background' => 'View All background', 'link_border' => 'View All border'] as $key => $label)
            <div class="col-md-4 form-group"><label for="heading-{{ $key }}">{{ $label }}</label><input class="form-control" style="height: 42px; padding: 4px;" type="color" id="heading-{{ $key }}" name="data[heading_{{ $key }}]" value="{{ old('data.heading_'.$key, data_get($section ?? null, 'data.heading_'.$key) ?: $defaultHeadingOptions[$key]) }}"></div>
        @endforeach
    </div>
</fieldset></div>
