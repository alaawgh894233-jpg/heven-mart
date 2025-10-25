<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id' => 'sometimes|exists:stores,id',
            'category_id' => 'sometimes|exists:categories,id',
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'description_ar' => 'sometimes|string',
            'description_en' => 'sometimes|string',
//            'details_ar' => 'sometimes|string',
//            'details_en' => 'sometimes|string',
            'stock' => 'sometimes|integer',
            'unit_en ' => 'nullable|string',
            'unit_ar' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'images_data' => 'nullable|array',
            'images_data.*.value_id' => 'nullable|exists:attribute_values,id',
            'images_data.*.is_primary' => 'nullable|boolean',
            'quantity' => 'sometimes|integer|min:0',
            'price' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'enum' => 'sometimes|string|in:enables,disabled',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'nullable|exists:attributes,id',
            'attributes.*.name_en' => 'required_without:attributes.*.attribute_id|string|max:255',
            'attributes.*.name_ar' => 'required_without:attributes.*.attribute_id|string|max:255',
            'attributes.*.value_id' => 'nullable|exists:attribute_values,id',
            'attributes.*.value_en' => 'required_without:attributes.*.value_id|string|max:255',
            'attributes.*.value_ar' => 'required_without:attributes.*.value_id|string|max:255',
            'attributes.*.values.*.price_impact' => 'nullable|numeric|min:0',
            'attributes.*.values.*.quantity' => 'nullable|integer|min:0',
        ];
    }
}
