<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest

{
    public function authorize(): bool
    {
        return true; // أو تخصيص حسب صلاحيات المستخدم
    }

    public function rules(): array
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string|in:cash,card',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'store_id.required' => 'Store ID is required.',
            'address_id.exists' => 'Selected address does not exist.',
            'items.required' => 'At least one item is required.',
            'items.*.product_id.exists' => 'Product does not exist.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
