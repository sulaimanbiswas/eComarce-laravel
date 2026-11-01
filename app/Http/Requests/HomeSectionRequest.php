<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class HomeSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->get('banner') && ! $this->get('content') && in_array($this->input('type'), ['pure-grid', 'carousel-grid'])) {
            $data = $this->input('data', []);
            if (empty($data['layout']) || $data['layout'] === 'standard') {
                $data['layout'] = $this->input('type') === 'carousel-grid' ? 'slider' : 'grid';
            }
            $data['rows'] = filled($data['rows'] ?? null) ? $data['rows'] : 2;
            $data['cols'] = filled($data['cols'] ?? null) ? $data['cols'] : 5;
            $this->merge(['type' => 'pure-grid', 'data' => $data]);
        }

        if ($this->get('banner')) {
            $this->merge([
                'title' => $this->input('title') ?: 'Promotional Banners',
                'type' => 'banner',
                'data' => array_merge([
                    'rows' => 1,
                    'cols' => 1,
                    'source' => null,
                ], $this->get('data', [])),
            ]);

            $images = Arr::get($this->get('data'), 'columns.image', []);
            if ($this->base_image_src) {
                array_push($images, str_replace(asset(''), '', $this->base_image_src));
            }

            $this->merge([
                'data' => array_merge($this->get('data'), [
                    'columns' => array_merge(Arr::get($this->get('data'), 'columns', []), [
                        'image' => $images,
                    ]),
                ]),
            ]);
        }

        if ($this->get('content')) {
            $this->merge([
                'type' => 'content',
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required',
            'type' => 'required',
            'items' => 'nullable|array',
            'categories' => 'nullable|array',
            'content' => 'nullable|string',
            'data.layout' => ['sometimes', Rule::in(array_keys(config('storefront.product_layouts')))],
            'data.showcase_heading' => ['nullable', 'string', 'max:120'],
            'data.showcase_limit' => ['sometimes', 'integer', 'between:1,48'],
            'data.section_enabled' => ['sometimes', 'boolean'],
            'data.showcase_columns' => ['sometimes', 'integer', 'between:2,6'],
            'data.showcase_view_all' => ['sometimes', 'boolean'],
            'data.showcase_filters' => ['sometimes', 'boolean'],
            'data.showcase_batch' => ['sometimes', 'integer', 'between:1,48'],
            'data.showcase_sort' => ['sometimes', 'in:newest,oldest,price-low,price-high,name'],
            'data.showcase_background' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.showcase_accent' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.heading_variant' => ['sometimes', Rule::in(array_merge(['inherit'], array_keys(config('storefront.heading_variants'))))],
            'data.heading_palette' => ['sometimes', 'in:inherit,custom'],
            'data.heading_link_text' => ['nullable', 'string', 'max:40'],
            'data.heading_title_color' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.heading_link_color' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.heading_link_hover' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.heading_accent_color' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.heading_link_background' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.heading_link_border' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
        ];

        // Only require rows/cols for product sections (not content or banner)
        if (! $this->get('content') && ! $this->get('banner') && $this->get('type') != 'content' && $this->get('type') != 'banner') {
            $rules += [
                'data.rows' => 'required|integer',
                'data.cols' => 'required|integer',
                'data.source' => 'nullable',
            ];
        }

        if ($this->get('banner') || $this->get('type') === 'banner') {
            $rules += [
                'data.columns' => ['required', 'array'],
                'data.columns.width' => ['required', 'array', 'min:1', 'max:4'],
                'data.columns.image.*' => ['nullable', 'string', 'max:2048'],
                'data.columns.mobile_image.*' => ['nullable', 'string', 'max:2048'],
                'data.columns.desktop_upload.*' => ['nullable', 'image', 'max:8192'],
                'data.columns.mobile_upload.*' => ['nullable', 'image', 'max:8192'],
                'data.columns.animation.*' => ['nullable', 'in:fade-left,fade-right,fade-up,fade-down'],
                'data.columns.link.*' => ['nullable', 'string', 'max:2048', 'regex:~^(?:#|/(?!/)|https?://)~i'],
                'data.columns.width.*' => ['required', 'integer', 'in:3,4,6,12'],
                'data.columns.alt.*' => ['nullable', 'string', 'max:200'],
                'data.columns.categories.*' => 'nullable|array',
                'data.columns.categories.*.*' => ['integer', 'exists:categories,id'],
                'data.banner_enabled' => ['sometimes', 'boolean'],
                'data.banner_background' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
                'data.banner_radius' => ['sometimes', 'integer', 'between:0,32'],
                'data.banner_spacing' => ['sometimes', 'integer', 'between:12,64'],
                'data.banner_fit' => ['sometimes', 'in:cover,contain'],
                'data.banner_ratio' => ['sometimes', 'in:auto,wide,landscape'],
                'data.banner_new_tab' => ['sometimes', 'boolean'],
            ];

            foreach (array_keys((array) $this->input('data.columns.width', [])) as $index) {
                $rules['data.columns.image.'.$index] = ['required_without:data.columns.desktop_upload.'.$index, 'nullable', 'string', 'max:2048'];
            }
        }

        if ($this->get('content') || $this->get('type') == 'content') {
            $rules += [
                'data.page_id' => 'required|exists:pages,id',
            ];
        }

        // Add SEO validation rules
        $rules += [
            'seo.title' => ['nullable', 'string', 'max:255'],
            'seo.description' => ['nullable', 'string', 'max:500'],
            'seo.image' => ['nullable', 'url', 'max:500'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'data.layout.in' => 'Choose one of the available section templates.',
            'data.showcase_limit.between' => 'Choose between 1 and 48 products.',
            'data.showcase_background.regex' => 'Choose a valid section background color.',
            'data.showcase_accent.regex' => 'Choose a valid heading accent color.',
            'data.columns.image.*.required_without' => 'Choose a desktop image for each banner.',
            'data.columns.desktop_upload.*.image' => 'Each desktop banner must be a valid image.',
            'data.columns.mobile_upload.*.image' => 'Each mobile banner must be a valid image.',
            'data.columns.desktop_upload.*.max' => 'Desktop banner images must be smaller than 8 MB.',
            'data.columns.mobile_upload.*.max' => 'Mobile banner images must be smaller than 8 MB.',
            'data.columns.link.*.regex' => 'Banner links must start with /, https://, or http://.',
        ];
    }
}
