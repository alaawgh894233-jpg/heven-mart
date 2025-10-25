<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
        $status = Status::fromBool($this->is_active);

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->{'name_'.$lang},
            'image' => asset('storage/'.$this->image),
            'is_active' => $this->is_active,
            'status' => $lang === 'en' ? $status->labelEn() : $status->labelAr(),
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
        ];
    }

}
