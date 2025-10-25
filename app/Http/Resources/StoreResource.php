<?php

namespace App\Http\Resources;

use App\Services\ImageService;
use Illuminate\support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    protected $imageService ;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->imageService = new ImageService();
    }
    public function toArray(Request $request): array
    {
        $lang = $request->header('lang');
        return [
            'id' => $this->id,
            'name' => $this->{'name_'.$lang},
            'description' => $this->{'description_'.$lang},
            'logo' => $this->imageService->getUrl($this->logo),
            'rating' => $this->rating,
            'num_of_rate' => $this->num_of_rate,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'address' => new AddressResource($this->whenLoaded('address')),
            'owner' => [
                'email' => $this->user->email,
                'name' => optional($this->user->profile)->first_name.''.optional($this->user->profile)->last_name
                ]
            ];
    }
}
