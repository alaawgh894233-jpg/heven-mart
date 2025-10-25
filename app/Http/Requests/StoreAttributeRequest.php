<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
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
// App\Http\Requests\StoreAttributeRequest

    public function rules()
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'category_ids' => 'nullable|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'options' => 'required|array|min:1',
            'options.*.value_ar' => 'required|string|max:255',
            'options.*.value_en' => 'required|string|max:255',
        ];
    }

}
