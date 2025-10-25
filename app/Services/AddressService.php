<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;


class AddressService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createForUser(User $user, array $data): Address
    {
        $datareverse = $this->getAddressFromCoordinates($data['lat'], $data['lon']);
        $data['address_ar'] = $datareverse['ar'];
        $data['address_en'] = $datareverse['en'];
        if(!$user->addresses()->exists()){
            $data['is_default'] = true;
        }else{
            if(isset($data['is_default']) && $data['is_default'] == true){
                $user->addresses()->update(['is_default' => false]);
            }
        }
        return $user->addresses()->create($data);
    }

    public function createForStore(Store $store, array $data): Address
    {
        $datareverse = $this->getAddressFromCoordinates($data['lat'], $data['lon']);
        $data['address_ar'] = $datareverse['ar'];
        $data['address_en'] = $datareverse['en'];
        $data['type'] = 'store';
        return $store->address()->create($data);
    }

    public function updateForStore(Store $store, array $data)
    {
        if(isset($data['lat'])&& isset($data['lon'])){
            $datareverse = $this->getAddressFromCoordinates($data['lat'], $data['lon']);
            $data['address_ar'] = $datareverse['ar'];
            $data['address_en'] = $datareverse['en'];
        }else{
            unset($data['lat']);
            unset($data['lon']);
        }
        $address = Address::where('store_id', $store->id)->first();
        $address->update($data);
        return $address->fresh();
    }

    public function updateForUser(Address $address,User $user, array $data)
    {
        if(isset($data['lat'])&& isset($data['lon'])){
            $datareverse = $this->getAddressFromCoordinates($data['lat'], $data['lon']);
            $data['address_ar'] = $datareverse['ar'];
            $data['address_en'] = $datareverse['en'];
        }else{
            unset($data['lat']);
            unset($data['lon']);
        }
        if(isset($data['is_default']) && $data['is_default'] == true){
            $user->addresses()->update(['is_default' => false]);
        }
        return $$address->update($data);
    }

    public function delete(Address $address): bool
    {
     return $address->delete();
    }
    public function getStoreAddress(Store $store): Address
    {
        return $store->address;
    }
    public function getUserAddresses(User $user): Collection
    {
        return $user->addresses()->orderBy('is_default','asc')->get();
    }


    function getAddressFromCoordinates($lat, $lon): array
    {
        $results = [];
        //en
        $response = Http::withHeaders([
            'User-Agent' => 'HeavenMart/1.0 (nouruddindibo@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json',
            'accept-language' => 'en',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $results['en'] = $data['display_name'] ?? 'No address found';
        }
        //ar
        $response = Http::withHeaders([
            'User-Agent' => 'HeavenMart/1.0 (nouruddindibo@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json',
            'accept-language' => 'ar',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $results['ar'] = $data['display_name'] ?? 'عنوان غير معروف';
        }
        return $results;
    }

}
