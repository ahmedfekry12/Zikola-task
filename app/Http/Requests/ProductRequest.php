<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price|min:0',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'rate' => 'nullable|numeric|between:0,5',
            'status' => 'required|in:active,draft,archived',
        ];
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'The selected category does not exist.',
            'compare_price.gt' => 'The compare price must be greater than the price.',
            'quantity.min' => 'The quantity must be at least 1.',
            'price.min' => 'The price must be at least 0.',
            'compare_price.min' => 'The compare price must be at least 0.',
            'rate.between' => 'The rate must be between 0 and 5.',
        ];
    }
}
