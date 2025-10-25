<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
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
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'description_ar' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
            'addrerss_details' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'lat' => 'sometimes|numeric|between:-90,90',
            'lon' => 'sometimes|numeric|between:-180,180',
        ];
    }
}
