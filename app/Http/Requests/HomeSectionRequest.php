<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class HomeSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->get('banner')) {
            $this->merge([
                'title' => 'Banner',
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
            'data.layout' => ['sometimes', 'in:standard,showcase'],
            'data.showcase_heading' => ['nullable', 'string', 'max:120'],
            'data.showcase_limit' => ['sometimes', 'integer', 'between:1,12'],
            'data.showcase_actions' => ['sometimes', 'in:both,default'],
            'data.showcase_background' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'data.showcase_accent' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
        ];

        // Only require rows/cols for product sections (not content or banner)
        if (! $this->get('content') && ! $this->get('banner') && $this->get('type') != 'content' && $this->get('type') != 'banner') {
            $rules += [
                'data.rows' => 'required|integer',
                'data.cols' => 'required|integer',
                'data.source' => 'nullable',
            ];
        }

        if ($this->get('banner')) {
            $rules += [
                'data.columns' => 'required|array',
                'data.columns.image.*' => 'required|string',
                'data.columns.animation.*' => 'required|string',
                'data.columns.link.*' => 'nullable|string',
                'data.columns.width.*' => 'required|numeric',
                'data.columns.categories.*' => 'nullable|array',
            ];
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
            'data.layout.in' => 'Choose the standard or best selling showcase layout.',
            'data.showcase_limit.between' => 'The showcase can display between 1 and 12 products.',
            'data.showcase_background.regex' => 'Choose a valid section background color.',
            'data.showcase_accent.regex' => 'Choose a valid heading accent color.',
            'data.showcase_actions.in' => 'Choose both card buttons or the store default button.',
        ];
    }
}
