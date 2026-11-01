<div class="col-12">
    <fieldset class="border rounded p-3 mb-3">
        <legend class="w-auto px-2" style="font-size: 15px;">Product Section Design</legend>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="section-layout">Layout</label>
                <select name="data[layout]" id="section-layout" class="form-control">
                    <option value="showcase" @selected(old('data.layout', $section->data->layout ?? 'showcase') === 'showcase')>Best selling showcase — featured + mixed cards</option>
                    <option value="standard" @selected(old('data.layout', $section->data->layout ?? 'showcase') === 'standard')>Standard grid / carousel</option>
                </select>
                <small class="form-text text-muted">The showcase uses your selected products and their order. Pin your best sellers first; the first product gets the large card.</small>
                <x-error field="data.layout" />
            </div>
            <div class="col-md-6 form-group">
                <label for="showcase-heading">Showcase heading</label>
                <input class="form-control" name="data[showcase_heading]" id="showcase-heading" maxlength="120" value="{{ old('data.showcase_heading', $section->data->showcase_heading ?? '') }}" placeholder="Best Selling Products">
                <x-error field="data.showcase_heading" />
            </div>
            <div class="col-md-6 form-group">
                <label for="showcase-actions">Card buttons</label>
                <select class="form-control" name="data[showcase_actions]" id="showcase-actions">
                    <option value="both" @selected(old('data.showcase_actions', $section->data->showcase_actions ?? 'both') === 'both')>Add to cart + Buy now</option>
                    <option value="default" @selected(old('data.showcase_actions', $section->data->showcase_actions ?? 'both') === 'default')>Use the store's default product button</option>
                </select>
                <x-error field="data.showcase_actions" />
            </div>
            <div class="col-md-6 form-group">
                <label for="showcase-limit">Maximum products</label>
                <input class="form-control" type="number" min="1" max="12" name="data[showcase_limit]" id="showcase-limit" value="{{ old('data.showcase_limit', $section->data->showcase_limit ?? 6) }}">
                <small class="form-text text-muted">6 products give the featured layout. With 1–3 products, balanced horizontal cards appear instead. Products never repeat.</small>
                <x-error field="data.showcase_limit" />
            </div>
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
