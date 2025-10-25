<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'en');
        $lang = in_array($lang, ['en', 'ar']) ? $lang : 'en';
        $status = Status::fromBool($this->is_active);

        return [
            'id' => $this->id,
            'name' => $this->{'name_' . $lang},
            'image'=> asset('storage/' . $this->image),
            'is_active' => $this->is_active,
            'status' => $lang === 'en' ? $status->labelEn() : $status->labelAr(),
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'status_ar' => $status->labelAr(),
            'status_en' => $status->labelEn(),
        ];
    }
}
