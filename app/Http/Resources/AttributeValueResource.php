<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'en');
        $lang = in_array($lang, ['en', 'ar']) ? $lang : 'en';
        return [
            'id' => $this->id,
            'value' => $this->{'name_' . $lang},
            'value_ar' => $this->name_ar,
            'value_en' => $this->name_en,
            'color_code' => $this->color_code
        ];
    }
}
