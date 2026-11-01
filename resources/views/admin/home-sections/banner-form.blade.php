@php
    $bannerData = (array) old('data', $section->data ?? []);
    $bannerColumns = (array) ($bannerData['columns'] ?? []);
    $bannerWidths = (array) ($bannerColumns['width'] ?? [6, 6]);
@endphp
<div data-banner-editor>
    <input type="hidden" name="type" value="banner">
    <div class="card-header p-3 d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
        <div><h4 class="mb-1">Promotional Banner Row</h4><p class="text-muted mb-0">One wide banner or matching banners side by side. Mobile images are optional.</p></div>
        <button type="submit" class="btn btn-primary">Save Banner Section</button>
    </div>
    <div class="card-body p-3">
        @if ($errors->any())<div class="alert alert-danger">@foreach ($errors->all() as $error)<p class="mb-1">{{ $error }}</p>@endforeach</div>@endif
        <div class="row">
            <div class="col-md-6 form-group"><label for="banner-title">Admin section name</label><input class="form-control" id="banner-title" name="title" maxlength="150" required value="{{ old('title', $section->title ?? 'Promotional Banners') }}"><small class="form-text text-muted">For organizing Home Sections; this name is not displayed on the storefront.</small></div>
            <div class="col-md-3 form-group"><label class="d-block" for="banner-enabled">Section visibility</label><input type="hidden" name="data[banner_enabled]" value="0"><input type="checkbox" id="banner-enabled" name="data[banner_enabled]" value="1" @checked($bannerData['banner_enabled'] ?? true)> <label for="banner-enabled">Show this row</label></div>
            <div class="col-md-3 form-group"><label class="d-block" for="banner-new-tab">Link behaviour</label><input type="hidden" name="data[banner_new_tab]" value="0"><input type="checkbox" id="banner-new-tab" name="data[banner_new_tab]" value="1" @checked($bannerData['banner_new_tab'] ?? false)> <label for="banner-new-tab">Open in new tab</label></div>
            <div class="col-md-3 form-group"><label for="banner-background">Background</label><input class="form-control" style="height: 40px; padding: 4px;" type="color" name="data[banner_background]" id="banner-background" value="{{ $bannerData['banner_background'] ?? '#fbf9f5' }}"></div>
            <div class="col-md-3 form-group"><label for="banner-radius">Corner radius (px)</label><input class="form-control" type="number" min="0" max="32" name="data[banner_radius]" id="banner-radius" value="{{ $bannerData['banner_radius'] ?? 16 }}"></div>
            <div class="col-md-3 form-group"><label for="banner-spacing">Top/bottom spacing (px)</label><input class="form-control" type="number" min="12" max="64" name="data[banner_spacing]" id="banner-spacing" value="{{ $bannerData['banner_spacing'] ?? 24 }}"></div>
            <div class="col-md-3 form-group"><label for="banner-fit">Image fit</label><select class="form-control" name="data[banner_fit]" id="banner-fit">@foreach (['cover' => 'Fill the banner', 'contain' => 'Show entire image'] as $key => $label)<option value="{{ $key }}" @selected(($bannerData['banner_fit'] ?? 'cover') === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-6 form-group"><label for="banner-ratio">Banner proportions</label><select class="form-control" name="data[banner_ratio]" id="banner-ratio">@foreach (['auto' => 'Original image proportions (recommended)', 'wide' => 'Wide promotional strip (4.6:1)', 'landscape' => 'Large campaign banner (3.8:1)'] as $key => $label)<option value="{{ $key }}" @selected(($bannerData['banner_ratio'] ?? 'auto') === $key)>{{ $label }}</option>@endforeach</select></div>
        </div>
        <p class="text-muted">Recommended: <strong>1400 × 370</strong> for one banner, <strong>900 × 200</strong> for each of two banners. Use similarly sized images for a level row. Each upload may be up to 8 MB.</p>
        <div data-banner-items>
            @foreach ($bannerWidths as $bannerIndex => $width)
                @include('admin.home-sections.banner-item', ['bannerIndex' => $bannerIndex, 'width' => $width, 'bannerColumns' => $bannerColumns])
            @endforeach
        </div>
        <template data-banner-template>@include('admin.home-sections.banner-item', ['bannerIndex' => '__INDEX__', 'width' => 6, 'bannerColumns' => []])</template>
        <div class="d-flex flex-wrap align-items-center" style="gap: 12px;"><button type="button" class="btn btn-outline-primary" data-banner-add>Add banner</button><span class="text-muted" data-banner-count aria-live="polite"></span></div>
        <div class="alert alert-light border mt-3 mb-0">To position this row between sections: save, open <a href="{{ route('admin.home-sections.index') }}">Home Sections</a>, then drag this row to the desired position.</div>
    </div>
</div>
@push('scripts')<script src="{{ versionedAsset('assets/js/banner-row-editor.js') }}" defer></script>@endpush
