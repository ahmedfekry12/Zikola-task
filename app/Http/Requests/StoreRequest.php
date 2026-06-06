<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The store name is required.',
            'name.string' => 'The store name must be a string.',
            'name.max' => 'The store name may not be greater than 255 characters.',
            'address.required' => 'The store address is required.',
            'address.string' => 'The store address must be a string.',
            'address.max' => 'The store address may not be greater than 255 characters.',
            'description.string' => 'The store description must be a string.',
            'logo_image.image' => 'The logo image must be an image file.',
            'logo_image.mimes' => 'The logo image must be a file of type: jpeg, png, jpg, gif, svg.',
            'logo_image.max' => 'The logo image may not be greater than 2048 kilobytes.',
            'cover_image.image' => 'The cover image must be an image file.',
            'cover_image.mimes' => 'The cover image must be a file of type: jpeg, png, jpg, gif, svg.',
            'cover_image.max' => 'The cover image may not be greater than 2048 kilobytes.',
        ];
    }
}
