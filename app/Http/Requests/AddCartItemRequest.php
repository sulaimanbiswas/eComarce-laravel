<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
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
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:10000'],
            'instance' => ['sometimes', 'string', 'in:default,kart,landing'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'This product is no longer available.',
            'quantity.min' => 'Product quantity must be at least one.',
            'instance.in' => 'Please select a valid cart.',
        ];
    }
}
