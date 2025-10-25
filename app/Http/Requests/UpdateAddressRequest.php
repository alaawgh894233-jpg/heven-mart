<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'lat' => 'sometimes|numeric|between:-90,90',
            'lon' => 'sometimes|numeric|between:-180,180',
            'address_details' => 'sometimes|string',
            'phone' => 'sometimes|string|between:10,20',
            'type'=> 'sometimes|string|in:home,work,other',
            'is_default' => 'sometimes|boolean', 
        ];
    }
}
