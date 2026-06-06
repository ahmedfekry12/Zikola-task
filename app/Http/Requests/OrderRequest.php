<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|string|max:255|unique:orders,number',
            'payment_method' => 'required|string|max:255',
            'status' => 'required|string|in:pending,processing,delivering,completed,cancelled,refunded',
            'payment_status' => 'required|string|in:pending,paid,failed',
            'delivery' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.options' => 'nullable|array',
            'products.*.options.*' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'Order number is required.',
            'number.string' => 'Order number must be a string.',
            'number.max' => 'Order number must not exceed 255 characters.',
            'number.unique' => 'Order number must be unique.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.string' => 'Payment method must be a string.',
            'payment_method.max' => 'Payment method must not exceed 255 characters.',
            'status.required' => 'Status is required.',
            'status.string' => 'Status must be a string.',
            'status.in' => 'Status must be one of the following: pending, processing, delivering, completed, cancelled, refunded.',
            'payment_status.required' => 'Payment status is required.',
            'payment_status.string' => 'Payment status must be a string.',
            'payment_status.in' => 'Payment status must be one of the following: pending, paid, failed.',
            'delivery.required' => 'Delivery cost is required.',
            'delivery.numeric' => 'Delivery cost must be a number.',
            'delivery.min' => 'Delivery cost must be at least 0.',
            'tax.required' => 'Tax is required.',
            'tax.numeric' => 'Tax must be a number.',
            'tax.min' => 'Tax must be at least 0.',
            'discount.required' => 'Discount is required.',
            'discount.numeric' => 'Discount must be a number.',
            'discount.min' => 'Discount must be at least 0.',
            'products.required' => 'Products are required.',
            'products.array' => 'Products must be an array.',
            'products.min' => 'At least one product is required.',
            'products.*.product_id.required' => 'Product ID is required for each product.',
            'products.*.product_id.exists' => 'Product ID must exist in the products table.',
            'products.*.quantity.required' => 'Quantity is required for each product.',
            'products.*.quantity.integer' => 'Quantity must be an integer for each product.',
            'products.*.quantity.min' => 'Quantity must be at least 1 for each product.',
        ];
    }
}
