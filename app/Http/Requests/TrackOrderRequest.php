<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TrackOrderRequest extends FormRequest
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
            'order' => ['nullable', 'integer', 'min:1'],
            'phone' => ['nullable', 'string', 'max:25'],
            'action' => ['nullable', 'string', 'in:resend,confirm'],
            'code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return ['order.integer' => 'Please enter a valid order ID.', 'phone.string' => 'Please enter the mobile number used for the order.'];
    }
}
