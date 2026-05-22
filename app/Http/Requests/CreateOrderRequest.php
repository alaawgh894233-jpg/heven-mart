<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * السماح للمستخدم بتنفيذ الطلب
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق (Validation Rules)
     */
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

    /**
     * رسائل الأخطاء المخصصة
     */
    public function messages(): array
    {
        return [
            'store_id.required' => 'Store ID is required.',
            'store_id.exists' => 'Selected store does not exist.',

            'address_id.required' => 'Address ID is required.',
            'address_id.exists' => 'Selected address does not exist.',

            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Payment method must be cash or card.',

            'items.required' => 'At least one item is required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one item must be provided.',

            'items.*.product_id.required' => 'Product ID is required.',
            'items.*.product_id.exists' => 'Selected product does not exist.',

            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.integer' => 'Quantity must be an integer.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
