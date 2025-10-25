<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'name' =>  $this->{'name_' . $lang},
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'values' => AttributeValueResource::collection($this->whenLoaded('options')),
        ];

    }
}
