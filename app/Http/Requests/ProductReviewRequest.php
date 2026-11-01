<?php

namespace App\Http\Requests;

use App\Services\OrderAccessService;
use Illuminate\Foundation\Http\FormRequest;

class ProductReviewRequest extends FormRequest
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
            'review' => ['required', 'string', 'min:10', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'order_id' => ['required', 'integer', 'min:1'],
            'phone_number' => ['required', 'string', 'regex:/^8801\d{9}$/'],
            'name' => ['nullable', 'string', 'max:255'],
            'recommend' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone_number', $this->input('phone'));
        if (is_string($phone)) {
            $this->merge(['phone_number' => app(OrderAccessService::class)->normalizePhone($phone)]);
        }
    }

    public function messages(): array
    {
        return [
            'order_id.integer' => 'Please provide a valid order ID.',
            'phone_number.regex' => 'Please provide the mobile number used for your order.',
            'review.min' => 'Review must be at least 10 characters.',
            'rating.required' => 'Please provide a rating.',
        ];
    }
}
