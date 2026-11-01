<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => (setting('show_option')->hide_phone_prefix ?? false) ? ['required', 'regex:/^\+8801\d{9}$/'] : ['required', 'regex:/^1\d{9}$/'],
            'email' => ['nullable', 'email'],
            'address' => ['required', 'string', 'max:500'],
            'note' => ['nullable', 'string', 'max:1000'],
            'shipping' => ['required', 'string', Rule::in(collect(setting('delivery_areas') ?? [])->pluck('name')->all())],
            'cart' => ['required', 'array', 'min:1', 'max:100'],
            'cart.*.id' => ['required', 'integer', 'min:1'],
            'cart.*.quantity' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone');
        if (! is_string($phone)) {
            return;
        }
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '8801')) {
            $phone = substr($phone, 2);
        }
        if (strlen($phone) === 11 && str_starts_with($phone, '01')) {
            $phone = (setting('show_option')->hide_phone_prefix ?? false) ? '+88'.$phone : substr($phone, 1);
        }
        $this->merge(['phone' => $phone]);
    }

    public function messages(): array
    {
        return ['phone.regex' => 'Please provide a valid mobile number.', 'shipping.in' => 'Please select an available delivery area.', 'cart.*.quantity.min' => 'Product quantity must be at least one.', 'cart.*.id.integer' => 'Please select a valid product.'];
    }
}
