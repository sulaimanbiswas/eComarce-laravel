<fieldset class="card mt-4" data-trust-editor>
    <div class="card-header p-3">
        <h4 class="mb-1">Homepage Trust & Services</h4>
        <p class="text-muted mb-0">Shown below Featured Categories. Add up to 12 items; choose 2, 3 or 4 columns on desktop. Smaller screens adjust automatically.</p>
    </div>
    <div class="card-body p-3">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="trust-heading">Section heading (optional)</label>
                <input class="form-control" id="trust-heading" name="services[heading]" maxlength="100" value="{{ $serviceSettings['heading'] }}" placeholder="Why shop with us">
            </div>
            <div class="col-md-3 form-group">
                <label for="trust-columns">Desktop columns</label>
                <select class="form-control" name="services[columns]" id="trust-columns">
                    @foreach ([2, 3, 4] as $columns)
                        <option value="{{ $columns }}" @selected((int) $serviceSettings['columns'] === $columns)>{{ $columns }} columns</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <input type="hidden" name="services[show_details]" value="0">
                <label for="trust-show-details" class="d-block">Descriptions</label>
                <input id="trust-show-details" type="checkbox" name="services[show_details]" value="1" @checked($serviceSettings['show_details'])>
                <label for="trust-show-details">Show descriptions</label>
            </div>
        </div>
        <div class="row">
            @foreach (['background_color' => 'Section background', 'card_color' => 'Card background', 'text_color' => 'Text', 'icon_color' => 'Icons', 'border_color' => 'Border & dividers'] as $key => $label)
                <div class="col-6 col-lg form-group">
                    <label for="trust-{{ $key }}">{{ $label }}</label>
                    <input class="form-control" style="height: 42px; padding: 4px;" type="color" id="trust-{{ $key }}" name="services[{{ $key }}]" value="{{ $serviceSettings[$key] }}">
                </div>
            @endforeach
        </div>
        <div data-trust-items>
            @foreach ($serviceItems as $item)
                @include('admin.settings.trust-service-item', ['itemIndex' => $loop->index, 'serviceItem' => $item])
            @endforeach
        </div>
        <template data-trust-template>
            @include('admin.settings.trust-service-item', ['itemIndex' => '__INDEX__', 'serviceItem' => ['title' => '', 'detail' => '', 'icon' => 'shield', 'enabled' => true, 'link' => '', 'order' => 0]])
        </template>
        <template data-trust-icon-templates>
            @foreach (config('storefront.trust_icons') as $iconKey => $iconLabel)
                <span data-icon="{{ $iconKey }}">@include('partials.trust-icon', ['icon' => $iconKey])</span>
            @endforeach
        </template>
        <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
            <button class="btn btn-outline-primary" type="button" data-trust-add>Add service item</button>
            <span class="text-muted" data-trust-count aria-live="polite"></span>
        </div>
        <p class="text-muted mt-3 mb-0">Use the Services Section switch above to show or hide this entire section. Changes take effect after you press Save.</p>
    </div>
</fieldset>

@push('scripts')
    <script src="{{ versionedAsset('assets/js/trust-services-editor.js') }}" defer></script>
@endpush
