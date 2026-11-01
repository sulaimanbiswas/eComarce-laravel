<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'logo.hero_banner.image' => 'Please choose a valid banner image.',
            'logo.hero_banner.max' => 'The hero banner must be smaller than 5 MB.',
            'company.hero_banner_link.regex' => 'Use a link starting with /, https://, or http://.',
            'company.hero_banner_alt.max' => 'The image description must not exceed 255 characters.',
            'services.items.min' => 'Keep at least one service item. You can switch off the whole section instead.',
            'services.items.max' => 'You can add up to 12 service items.',
            'services.items.*.title.required' => 'Each service item needs a title.',
            'services.items.*.link.regex' => 'Service links must start with /, https://, or http://.',
            'services.items.*.icon.in' => 'Please choose an icon from the available options.',
            'services.columns.between' => 'Choose 2, 3 or 4 desktop columns.',
            'services.items.*.order.max' => 'Service item positions must be between 1 and 12.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return [];
        }

        if ($this->get('tab') == 'company') {
            return [
                'logo' => ['sometimes', 'array'],
                'logo.*' => ['nullable', 'image'],
                'logo.hero_banner' => ['nullable', 'image', 'max:5120'],
                'company' => ['required', 'array'],
                'company.name' => ['required'],
                'company.contact_name' => ['required'],
                'company.email' => ['required'],
                'company.phone' => ['required'],
                'company.whatsapp' => ['nullable'],
                'company.tagline' => ['required'],
                'company.address' => ['required'],
                'company.office_time' => ['required'],
                'company.messenger' => ['nullable'],
                'company.gmap_ecode' => ['nullable'],
                'company.dev_name' => ['nullable'],
                'company.dev_link' => ['nullable'],
                'company.home_heading' => ['nullable', 'string', 'max:255'],
                'company.seo_title' => ['nullable', 'string', 'max:255'],
                'company.meta_description' => ['nullable', 'string', 'max:500'],
                'company.hero_banner_enabled' => ['sometimes', 'boolean'],
                'company.hero_banner_link' => ['nullable', 'string', 'max:2048', 'regex:~^(?:/(?!/)|https?://)~i'],
                'company.hero_banner_alt' => ['nullable', 'string', 'max:255'],
                'call_for_order' => ['required'],
                'social' => ['required', 'array'],
            ];
        }

        if ($this->get('tab') == 'delivery') {
            return [
                'delivery_areas' => 'required|array|min:1',
                'delivery_areas.*.name' => 'required|string|max:255',
                'delivery_areas.*.cost' => 'required|integer|min:0',
                'default_delivery_area' => 'required|integer',
                'delivery_text' => 'sometimes',
                'free_delivery' => 'sometimes',
                'show_option' => 'required|array',
            ];
        }

        if ($this->get('tab') == 'analytics') {
            return [
                'gtm_id' => 'sometimes',
                'pixel_ids' => 'sometimes',
                'meta_pixel' => 'sometimes',
                'scripts' => 'sometimes',
            ];
        }

        if ($this->get('tab') == 'courier') {
            return [
                'Pathao' => 'required|array',
                'SteadFast' => 'required|array',
            ];
        }

        if ($this->get('tab') == 'sms') {
            return [
                'ElitBuzz' => 'required|array',
                'BDWebs' => 'required|array',
                'SMSTemplates' => 'required|array',
                'show_option' => 'required|array',
            ];
        }

        if ($this->get('tab') == 'fraud') {
            return [
                'fraud' => 'required|array',
            ];
        }

        if ($this->get('tab') == 'color') {
            $rules = [];
            foreach (['topbar', 'header', 'search', 'navbar', 'category_menu', 'section', 'badge', 'footer', 'primary', 'add_to_cart', 'order_now'] as $key) {
                $rules['color.'.$key] = 'required|array';
                foreach (['background_color', 'background_hover', 'text_color', 'text_hover'] as $color) {
                    $rules['color.'.$key.'.'.$color] = ['required', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'];
                }
            }

            return $rules;
        }

        if (in_array($this->get('tab'), ['categories', 'brands'])) {
            return [
                'show_option' => 'required|array',
            ];
        }

        return [
            'products_page' => 'required|array',
            'related_products' => 'required|array',
            'scroll_text' => 'nullable',
            'show_option' => 'required|array',
            'discount_text' => 'nullable',
            'show_option.product_grid_button' => ['required', 'in:add_to_cart,order_now,both,none'],
            'services' => 'required|array',
            'section_heading' => ['sometimes', 'array'],
            'section_heading.variant' => ['sometimes', Rule::in(array_keys(config('storefront.heading_variants')))],
            'section_heading.link_text' => ['sometimes', 'required', 'string', 'max:40'],
            'section_heading.title_color' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'section_heading.link_color' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'section_heading.link_hover' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'section_heading.accent_color' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'section_heading.link_background' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'section_heading.link_border' => ['sometimes', 'regex:/^#[a-f0-9]{6}$/i'],
            'services.enabled' => ['required', 'boolean'],
            'services.heading' => ['nullable', 'string', 'max:100'],
            'services.show_details' => ['required', 'boolean'],
            'services.columns' => ['required', 'integer', 'between:2,4'],
            'services.background_color' => ['required', 'regex:/^#[a-f0-9]{6}$/i'],
            'services.card_color' => ['required', 'regex:/^#[a-f0-9]{6}$/i'],
            'services.text_color' => ['required', 'regex:/^#[a-f0-9]{6}$/i'],
            'services.icon_color' => ['required', 'regex:/^#[a-f0-9]{6}$/i'],
            'services.border_color' => ['required', 'regex:/^#[a-f0-9]{6}$/i'],
            'services.items' => ['required', 'array', 'min:1', 'max:12'],
            'services.items.*' => ['required', 'array:title,detail,icon,enabled,link,order'],
            'services.items.*.title' => ['required', 'string', 'max:120'],
            'services.items.*.detail' => ['nullable', 'string', 'max:255'],
            'services.items.*.icon' => ['required', Rule::in(array_keys(config('storefront.trust_icons')))],
            'services.items.*.enabled' => ['required', 'boolean'],
            'services.items.*.link' => ['nullable', 'string', 'max:2048', 'regex:~^(?:/(?!/)|https?://)~i'],
            'services.items.*.order' => ['required', 'integer', 'min:0', 'max:11'],
        ];
    }
}
