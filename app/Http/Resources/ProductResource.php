<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'category_id' => $this->category_id,
            'images' => $this->images->pluck('image'),
            'skus' => $this->skus->map(function ($sku) {
                $attributes = $sku->attributeOptions->mapWithKeys(function ($option) {
                    return [$option->attribute->name_ar => $option->value_ar];
                });

                return [
                    'id' => $sku->id,
                    'price' => $sku->price,
                    'stock' => $sku->stock,
                    'attributes' => $attributes,
                    'images' => $sku->images->pluck('image'),
                ];
            }),
        ];

    }
}
