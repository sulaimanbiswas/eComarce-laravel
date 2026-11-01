@php
    $desktopBanner = data_get($bannerColumns, 'image.'.$bannerIndex, '');
    $mobileBanner = data_get($bannerColumns, 'mobile_image.'.$bannerIndex, '');
@endphp
<div class="card border shadow-none mb-3" data-banner-item>
    <div class="card-header p-3 d-flex justify-content-between align-items-center"><strong data-banner-label>Banner</strong><button class="btn btn-outline-danger btn-sm" type="button" data-banner-remove>Remove</button></div>
    <div class="card-body p-3">
        <div class="row">
            @foreach (['desktop' => ['image', $desktopBanner], 'mobile' => ['mobile_image', $mobileBanner]] as $kind => [$imageKey, $imagePath])
                <div class="col-md-6 form-group">
                    <label data-banner-label-for="{{ $kind }}_upload" for="banner-{{ $kind }}_upload-{{ $bannerIndex }}">{{ ucfirst($kind) }} image {{ $kind === 'mobile' ? '(optional)' : '' }}</label>
                    <input type="hidden" name="data[columns][{{ $imageKey }}][{{ $bannerIndex }}]" data-banner-field="{{ $imageKey }}" value="{{ $imagePath }}">
                    <img data-banner-preview="{{ $kind }}" src="{{ $imagePath ? asset($imagePath) : '' }}" alt="{{ ucfirst($kind) }} banner preview" @if (!$imagePath) hidden @endif style="display: {{ $imagePath ? 'block' : 'none' }}; width: 100%; max-height: 170px; object-fit: contain; margin-bottom: 10px; border-radius: 10px; background: #faf9f6;">
                    <input class="form-control" type="file" accept="image/*" name="data[columns][{{ $kind }}_upload][{{ $bannerIndex }}]" data-banner-field="{{ $kind }}_upload" id="banner-{{ $kind }}_upload-{{ $bannerIndex }}" @if ($kind === 'desktop' && !$imagePath) required @endif>
                    @if ($kind === 'mobile')<small class="form-text text-muted">Uses the desktop image when empty.</small>@endif
                </div>
            @endforeach
            <div class="col-md-3 form-group"><label data-banner-label-for="width" for="banner-width-{{ $bannerIndex }}">Desktop width</label><select class="form-control" name="data[columns][width][{{ $bannerIndex }}]" data-banner-field="width" id="banner-width-{{ $bannerIndex }}">@foreach ([12 => 'Full width', 6 => 'Half width (2 per row)', 4 => 'One third', 3 => 'One quarter'] as $value => $label)<option value="{{ $value }}" @selected((int) $width === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-9 form-group"><label data-banner-label-for="link" for="banner-link-{{ $bannerIndex }}">Destination link (optional)</label><input class="form-control" name="data[columns][link][{{ $bannerIndex }}]" data-banner-field="link" id="banner-link-{{ $bannerIndex }}" value="{{ data_get($bannerColumns, 'link.'.$bannerIndex, '') }}" maxlength="2048" placeholder="/shop or https://example.com/offer"></div>
            <div class="col-12 form-group mb-0"><label data-banner-label-for="alt" for="banner-alt-{{ $bannerIndex }}">Image description</label><input class="form-control" name="data[columns][alt][{{ $bannerIndex }}]" data-banner-field="alt" id="banner-alt-{{ $bannerIndex }}" maxlength="200" value="{{ data_get($bannerColumns, 'alt.'.$bannerIndex, '') }}" placeholder="Describe the promotion for accessibility"></div>
            @foreach ((array) data_get($bannerColumns, 'categories.'.$bannerIndex, []) as $categoryId)<input type="hidden" name="data[columns][categories][{{ $bannerIndex }}][]" data-banner-field="categories" value="{{ $categoryId }}">@endforeach
        </div>
    </div>
</div>
