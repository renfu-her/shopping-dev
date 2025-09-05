<?php

namespace App\Http\Requests\Api;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class MemberRegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Member::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required', 'string'],
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
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
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
