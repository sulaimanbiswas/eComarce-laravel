<div class="card shadow-none border mb-3" data-trust-item>
    <div class="card-header p-3 d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
        <strong data-trust-item-title>Service item</strong>
        <div class="d-flex" style="gap: 6px;">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-trust-up aria-label="Move item up">Move up</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-trust-down aria-label="Move item down">Move down</button>
            <button type="button" class="btn btn-outline-danger btn-sm" data-trust-remove aria-label="Remove service item">Remove</button>
        </div>
    </div>
    <div class="card-body p-3">
        <input type="hidden" name="services[items][{{ $itemIndex }}][order]" data-trust-field="order" value="{{ $itemIndex }}">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="trust-title-{{ $itemIndex }}" data-trust-label="title">Title</label>
                <input class="form-control" id="trust-title-{{ $itemIndex }}" name="services[items][{{ $itemIndex }}][title]" data-trust-field="title" value="{{ $serviceItem['title'] }}" maxlength="120" required placeholder="e.g. Quality checked products">
            </div>
            <div class="col-md-4 form-group">
                <label for="trust-icon-{{ $itemIndex }}" data-trust-label="icon">Icon</label>
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <span data-trust-icon-preview style="color: #b78024; display: flex;">@include('partials.trust-icon', ['icon' => $serviceItem['icon']])</span>
                    <select class="form-control" id="trust-icon-{{ $itemIndex }}" name="services[items][{{ $itemIndex }}][icon]" data-trust-field="icon">
                        @foreach (config('storefront.trust_icons') as $iconKey => $iconLabel)
                            <option value="{{ $iconKey }}" @selected($serviceItem['icon'] === $iconKey)>{{ $iconLabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 form-group">
                <label for="trust-enabled-{{ $itemIndex }}" data-trust-label="enabled" class="d-block">Visibility</label>
                <input type="hidden" name="services[items][{{ $itemIndex }}][enabled]" data-trust-field="enabled" value="0">
                <input type="checkbox" id="trust-enabled-{{ $itemIndex }}" name="services[items][{{ $itemIndex }}][enabled]" data-trust-field="enabled" value="1" @checked($serviceItem['enabled']) aria-label="Show this service item">
                <span>Visible</span>
            </div>
            <div class="col-md-6 form-group mb-md-0">
                <label for="trust-detail-{{ $itemIndex }}" data-trust-label="detail">Description (optional)</label>
                <input class="form-control" id="trust-detail-{{ $itemIndex }}" name="services[items][{{ $itemIndex }}][detail]" data-trust-field="detail" value="{{ $serviceItem['detail'] }}" maxlength="255">
            </div>
            <div class="col-md-6 form-group mb-0">
                <label for="trust-link-{{ $itemIndex }}" data-trust-label="link">Link (optional)</label>
                <input class="form-control" id="trust-link-{{ $itemIndex }}" name="services[items][{{ $itemIndex }}][link]" data-trust-field="link" value="{{ $serviceItem['link'] }}" maxlength="2048" placeholder="/delivery-information or https://example.com">
            </div>
        </div>
    </div>
</div>
