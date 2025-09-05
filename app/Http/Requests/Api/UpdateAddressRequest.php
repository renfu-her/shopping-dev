<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'in:shipping,billing'],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'address_line_1' => ['sometimes', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
            'state' => ['sometimes', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'country' => ['sometimes', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.in' => 'Address type must be either shipping or billing.',
            'first_name.string' => 'First name must be a string.',
            'first_name.max' => 'First name cannot exceed 255 characters.',
            'last_name.string' => 'Last name must be a string.',
            'last_name.max' => 'Last name cannot exceed 255 characters.',
            'company.max' => 'Company name cannot exceed 255 characters.',
            'address_line_1.string' => 'Address line 1 must be a string.',
            'address_line_1.max' => 'Address line 1 cannot exceed 255 characters.',
            'address_line_2.max' => 'Address line 2 cannot exceed 255 characters.',
            'city.string' => 'City must be a string.',
            'city.max' => 'City cannot exceed 255 characters.',
            'state.string' => 'State must be a string.',
            'state.max' => 'State cannot exceed 255 characters.',
            'postal_code.string' => 'Postal code must be a string.',
            'postal_code.max' => 'Postal code cannot exceed 20 characters.',
            'country.string' => 'Country must be a string.',
            'country.max' => 'Country cannot exceed 255 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
        ];
    }
}
