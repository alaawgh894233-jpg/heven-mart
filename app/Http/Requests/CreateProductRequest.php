<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        // فكّ images_meta إذا إجا كنص JSON
        if (is_string($this->images_meta)) {
            $decoded = json_decode($this->images_meta, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['images_meta' => $decoded]);
            }
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'nullable|exists:categories,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'price' => 'required|numeric|min:0',
//            'details_en' => 'required|string',
            'stock' => 'nullable|integer',
            'unit_en' => 'nullable|string',
            'unit_ar' => 'nullable|string',
            'images' => 'nullable|array',
        'images.*.file' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        'images.*.is_primary' => 'nullable|boolean',
        'images.*.attribute_value_id' => 'nullable|integer|exists:attribute_values,id',

        // السمات
        'attributes' => 'nullable|array',
        'attributes.*.attribute_id' => 'required|integer|exists:attributes,id',
        'attributes.*.values' => 'required|array',
        'attributes.*.values.*.value_id' => 'nullable|integer|exists:attribute_values,id',
        'attributes.*.values.*.value_en' => 'required_without:attributes.*.values.*.value_id|string',
        'attributes.*.values.*.value_ar' => 'required_without:attributes.*.values.*.value_id|string',
        'attributes.*.values.*.price_impact' => 'nullable|numeric',
        'attributes.*.values.*.quantity' => 'nullable|integer',
    ];
    }

    public function messages(): array
    {
        return [
            'images_meta.*.attribute_value_id.required_with' => 'لازم تحدد attribute_value_id لكل صورة.',
        ];
    }
}
