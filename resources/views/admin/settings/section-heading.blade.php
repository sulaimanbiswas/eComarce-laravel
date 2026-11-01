@php $headingOptions = array_replace(config('storefront.heading_defaults'), (array) setting('section_heading')); @endphp
<fieldset class="card mt-4">
    <div class="card-header p-3"><h4 class="mb-1">Section Titles & View All</h4><p class="text-muted mb-0">One consistent heading style for all homepage collections. Individual sections can override these defaults in Home Sections.</p></div>
    <div class="card-body p-3">
        <div class="row">
            <div class="col-md-6 form-group"><label for="heading-default-variant">Default heading layout</label><select class="form-control" id="heading-default-variant" name="section_heading[variant]">@foreach (config('storefront.heading_variants') as $key => $label)<option value="{{ $key }}" @selected(old('section_heading.variant', $headingOptions['variant']) === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-6 form-group"><label for="heading-default-text">View All label</label><input class="form-control" id="heading-default-text" name="section_heading[link_text]" maxlength="40" required value="{{ old('section_heading.link_text', $headingOptions['link_text']) }}"></div>
            @foreach (['title_color' => 'Title color', 'link_color' => 'View All text', 'link_hover' => 'View All hover text', 'accent_color' => 'Title underline / accent', 'link_background' => 'View All background', 'link_border' => 'View All border / divider'] as $key => $label)
                <div class="col-md-4 form-group"><label for="heading-default-{{ $key }}">{{ $label }}</label><input class="form-control" style="height: 42px; padding: 4px;" type="color" id="heading-default-{{ $key }}" name="section_heading[{{ $key }}]" value="{{ old('section_heading.'.$key, $headingOptions[$key]) }}"></div>
            @endforeach
        </div>
        <div class="row">@foreach (config('storefront.heading_variants') as $key => $label)<div class="col-sm-6 col-lg mb-3"><img class="w-100 border rounded mb-2" src="{{ asset('assets/images/layouts/heading-'.$key.'.svg') }}" alt="{{ $label }} preview" width="240" height="100"><small>{{ $label }}</small></div>@endforeach</div>
    </div>
</fieldset>
