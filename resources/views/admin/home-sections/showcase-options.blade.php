<div class="col-12">
    <fieldset class="border rounded p-3 mb-3">
        <legend class="w-auto px-2" style="font-size: 15px;">Product Section Design</legend>
        @php $selectedLayout = old('data.layout', isset($section) ? $section->storefrontLayout() : 'grid'); @endphp
        <div class="section-template-picker mb-4" role="group" aria-label="Product section templates">
            @foreach (config('storefront.product_layouts') as $layoutKey => $layoutInfo)
                <label class="section-template-option">
                    <input type="radio" name="data[layout]" value="{{ $layoutKey }}" @checked($selectedLayout === $layoutKey)>
                    <span class="section-template-option__body"><img src="{{ asset('assets/images/layouts/'.$layoutInfo['preview'].'.svg') }}" alt="{{ $layoutInfo['name'] }} layout preview" width="240" height="136"><strong>{{ $layoutInfo['name'] }}</strong><small>{{ $layoutInfo['description'] }}</small></span>
                </label>
            @endforeach
        </div>
        <x-error field="data.layout" />
        <div class="row">
            @include('admin.home-sections.heading-options')
            <div class="col-md-6 form-group">
                <input type="hidden" name="data[section_enabled]" value="0">
                <input type="checkbox" name="data[section_enabled]" id="section-enabled" value="1" @checked(old('data.section_enabled', $section->data->section_enabled ?? true))> <label for="section-enabled">Show this section</label>
                <p class="text-muted small mb-0">All Products is always placed at the bottom. Other sections follow the order on the Home Sections page.</p>
            </div>
            <div class="col-md-6 form-group">
                <label for="showcase-heading">Showcase heading</label>
                <input class="form-control" name="data[showcase_heading]" id="showcase-heading" maxlength="120" value="{{ old('data.showcase_heading', $section->data->showcase_heading ?? '') }}" placeholder="Best Selling Products">
                <x-error field="data.showcase_heading" />
            </div>
            <div class="col-md-6 form-group">
                <label>Product card buttons</label>
                <p class="text-muted mb-0">All sections use the same buttons. Choose Add to Cart, Buy Now, both or neither in <a href="{{ route('admin.settings', ['tab' => 'others']) }}">Settings → Others → Product Card Buttons</a>.</p>
            </div>
            <div class="col-md-6 form-group">
                <label for="showcase-limit">Maximum products</label>
                <input class="form-control" type="number" min="1" max="48" name="data[showcase_limit]" id="showcase-limit" value="{{ old('data.showcase_limit', $section->data->showcase_limit ?? 6) }}">
                <small class="form-text text-muted">1–48 products. For All Products this is the initial count; Load More adds the batch below. Featured Mix looks best with 6.</small>
                <x-error field="data.showcase_limit" />
            </div>
            <div class="col-md-4 form-group"><label for="section-columns">Desktop columns</label><select class="form-control" id="section-columns" name="data[showcase_columns]">@foreach (range(2, 6) as $columns)<option value="{{ $columns }}" @selected((int) old('data.showcase_columns', $section->data->showcase_columns ?? 5) === $columns)>{{ $columns }}</option>@endforeach</select><small class="text-muted">Applies to grids, sliders and horizontal layouts.</small></div>
            <div class="col-md-4 form-group"><label for="section-batch">Load More batch size</label><input class="form-control" id="section-batch" type="number" name="data[showcase_batch]" min="1" max="48" value="{{ old('data.showcase_batch', $section->data->showcase_batch ?? 10) }}"></div>
            <div class="col-md-4 form-group"><label for="section-sort">All Products sorting</label><select class="form-control" id="section-sort" name="data[showcase_sort]">@foreach (['newest' => 'Newest first', 'oldest' => 'Oldest first', 'price-low' => 'Price: low to high', 'price-high' => 'Price: high to low', 'name' => 'Name A–Z'] as $sort => $label)<option value="{{ $sort }}" @selected(old('data.showcase_sort', $section->data->showcase_sort ?? 'newest') === $sort)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-6 form-group"><input type="hidden" name="data[showcase_view_all]" value="0"><input type="checkbox" id="section-view-all" name="data[showcase_view_all]" value="1" @checked(old('data.showcase_view_all', $section->data->showcase_view_all ?? true))> <label for="section-view-all">Show View All link</label></div>
            <div class="col-md-6 form-group"><input type="hidden" name="data[showcase_filters]" value="0"><input type="checkbox" id="section-filters" name="data[showcase_filters]" value="1" @checked(old('data.showcase_filters', $section->data->showcase_filters ?? true))> <label for="section-filters">Show category filters in All Products</label></div>
            @foreach (['background' => ['Section background', '#eaf2e8'], 'accent' => ['Heading accent', '#c89536']] as $key => [$label, $default])
                <div class="col-md-4 form-group mb-md-0">
                    <label for="showcase-{{ $key }}">{{ $label }}</label>
                    <input class="form-control" type="color" style="height: 40px; padding: 4px;" name="data[showcase_{{ $key }}]" id="showcase-{{ $key }}" value="{{ old('data.showcase_'.$key, data_get($section ?? null, 'data.showcase_'.$key, $default)) }}">
                    <x-error :field="'data.showcase_'.$key" />
                </div>
            @endforeach
        </div>
    </fieldset>
</div>
@once
    @push('styles')
        <style>
            .section-template-picker { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 12px; }
            .section-template-option { position: relative; margin: 0; cursor: pointer; }
            .section-template-option input { position: absolute; top: 12px; right: 12px; z-index: 1; accent-color: #245c49; }
            .section-template-option__body { display: flex; flex-direction: column; gap: 7px; height: 100%; border: 2px solid #e8ebe8; border-radius: 10px; padding: 10px; background: #fff; }
            .section-template-option__body img { display: block; width: 100%; height: auto; border-radius: 5px; }
            .section-template-option__body small { color: #6c786e; line-height: 1.45; }
            .section-template-option input:checked + span { border-color: #245c49; box-shadow: 0 0 0 2px #245c4910; }
            .section-template-option input:focus-visible + span { outline: 2px solid #245c49; outline-offset: 3px; }
        </style>
    @endpush
@endonce
