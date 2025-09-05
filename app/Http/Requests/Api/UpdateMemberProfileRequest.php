<?php

namespace App\Http\Requests\Api;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberProfileRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique(Member::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.in' => 'Gender must be male, female, or other.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'city.max' => 'City cannot exceed 255 characters.',
            'state.max' => 'State cannot exceed 255 characters.',
            'postal_code.max' => 'Postal code cannot exceed 20 characters.',
            'country.max' => 'Country cannot exceed 255 characters.',
        ];
    }
}
