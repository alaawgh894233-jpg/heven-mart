<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language');
        $lang = in_array($lang, ['ar', 'en']) ? $lang : 'en';
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->{'name_' . $lang},
            'image' => $this->image,
            'is_leaf' => $this->children()->count() > 0 ? false : true,
        ];
    }
}
