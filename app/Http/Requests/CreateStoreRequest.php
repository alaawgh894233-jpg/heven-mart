<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStoreRequest extends FormRequest
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
            'name_ar'=>'required|string|max:255',
            'name_en'=>'required|string|max:255',
            'description_ar'=>'required|string',
            'description_en'=>'required|string',
            'logo'=>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'address_details'=>'nullable|string',
            'phone'=>'nullable|string',
            'lat'=>'required|numeric|between:-90,90',
            'lon'=>'required|numeric|between:-180,180',
        ];
    }
}
