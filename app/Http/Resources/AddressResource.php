<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $lang  = $request->header('accept-language','en');
        $lang = in_array($lang, ['ar', 'en']) ? $lang : 'en';
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'address' => $this->{'address_'.$lang},
            'address_details' => $this->address_details,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'lon' => $this ->lon
        ];
    }
}
