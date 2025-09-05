<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'payment_method' => ['required', 'string', 'in:credit_card,paypal,stripe,bank_transfer'],
            'shipping_address' => ['required', 'array'],
            'shipping_address.first_name' => ['required', 'string', 'max:255'],
            'shipping_address.last_name' => ['required', 'string', 'max:255'],
            'shipping_address.company' => ['nullable', 'string', 'max:255'],
            'shipping_address.address_line_1' => ['required', 'string', 'max:255'],
            'shipping_address.address_line_2' => ['nullable', 'string', 'max:255'],
            'shipping_address.city' => ['required', 'string', 'max:255'],
            'shipping_address.state' => ['required', 'string', 'max:255'],
            'shipping_address.postal_code' => ['required', 'string', 'max:20'],
            'shipping_address.country' => ['required', 'string', 'max:255'],
            'shipping_address.phone' => ['nullable', 'string', 'max:20'],
            'billing_address' => ['sometimes', 'array'],
            'billing_address.first_name' => ['required_with:billing_address', 'string', 'max:255'],
            'billing_address.last_name' => ['required_with:billing_address', 'string', 'max:255'],
            'billing_address.company' => ['nullable', 'string', 'max:255'],
            'billing_address.address_line_1' => ['required_with:billing_address', 'string', 'max:255'],
            'billing_address.address_line_2' => ['nullable', 'string', 'max:255'],
            'billing_address.city' => ['required_with:billing_address', 'string', 'max:255'],
            'billing_address.state' => ['required_with:billing_address', 'string', 'max:255'],
            'billing_address.postal_code' => ['required_with:billing_address', 'string', 'max:20'],
            'billing_address.country' => ['required_with:billing_address', 'string', 'max:255'],
            'billing_address.phone' => ['nullable', 'string', 'max:20'],
            'tax_amount' => ['sometimes', 'numeric', 'min:0'],
            'shipping_amount' => ['sometimes', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_address.array' => 'Shipping address must be an object.',
            'shipping_address.first_name.required' => 'Shipping first name is required.',
            'shipping_address.last_name.required' => 'Shipping last name is required.',
            'shipping_address.address_line_1.required' => 'Shipping address line 1 is required.',
            'shipping_address.city.required' => 'Shipping city is required.',
            'shipping_address.state.required' => 'Shipping state is required.',
            'shipping_address.postal_code.required' => 'Shipping postal code is required.',
            'shipping_address.country.required' => 'Shipping country is required.',
            'billing_address.array' => 'Billing address must be an object.',
            'billing_address.first_name.required_with' => 'Billing first name is required when billing address is provided.',
            'billing_address.last_name.required_with' => 'Billing last name is required when billing address is provided.',
            'billing_address.address_line_1.required_with' => 'Billing address line 1 is required when billing address is provided.',
            'billing_address.city.required_with' => 'Billing city is required when billing address is provided.',
            'billing_address.state.required_with' => 'Billing state is required when billing address is provided.',
            'billing_address.postal_code.required_with' => 'Billing postal code is required when billing address is provided.',
            'billing_address.country.required_with' => 'Billing country is required when billing address is provided.',
            'tax_amount.numeric' => 'Tax amount must be a number.',
            'tax_amount.min' => 'Tax amount cannot be negative.',
            'shipping_amount.numeric' => 'Shipping amount must be a number.',
            'shipping_amount.min' => 'Shipping amount cannot be negative.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }
}
