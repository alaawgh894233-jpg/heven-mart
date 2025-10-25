<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreService
{
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    // find Store by id
    public function findById(int $id)
    {
            return Store::findOrFail($id);
    }

    public function findByUser(User $user)//return store or null
    {
        return $user->store()->first();
    }

    public function getAllForUser(int $per_page, int $page, $filters)
    {
        return Store::UserFiltered($filters)->with('user.profile')->paginate($per_page, ['*'], 'page', $page);
    }
//            ->paginate($per_page, ['*'], 'page', $page);
//        $data = $stores->map(function ($store) {
//        return [
//            'id' => $store->id,
//            'name' => $store->name,
//            //'email' => $store->user()->first()->email,
//            //'username' => $store->user()->first()->profile()->first_name.' '.$store->user()->first()->profile()->last_name,
//            'description' => $store->description,
//            'rating' => $store->rating,
//            'num_of_rate' => $store->num_of_rate,
//            'logo' => $store->logo,
//
//        ];
//    });
//        return [
//            'currentPageItems' => $data,
//            'total' => $stores->total(),
//            'per_page' => $stores->perPage(),
//            'current_page' => $stores->currentPage(),
//            'last_page' => $stores->lastPage(),
//            'from' => $stores->firstItem(),
//            'to' => $stores->lastItem(),
//        ];
//    }

    // get stores by name
    public function getAllForAdmin(int $per_page, int $page, $filters)
    {
        return Store::with(['user.profile', 'address'])->AdminFiltered($filters)->paginate($per_page, ['*'], 'page', $page);
    }

//        $stores = Store::FilterAdmin($filters)->select('id', "name_{$lang} as name", "description_{$lang} as description",'rating','num_of_rate','status_approve','logo')
//                ->paginate($per_page, ['*'], 'page', $page);
//        $data = $stores->map(function ($store) {
//                    return [
//                        'id' => $store->id,
//                        'name' => $store->name,
//                        //'email' => $store->user()->first()->email,
//                        //'username' => $store->user()->first()->profile()->first_name.' '.$store->user()->first()->profile()->last_name,
//                        'description' => $store->description,
//                        'rating' => $store->rating,
//                        'num_of_rate' => $store->num_of_rate,
//                        'status_approve' => $store->status_approve,
//                        'logo' => $store->logo,
//
//                    ];
//            });
//        return [
//            'currentPageItems' => $data,
//            'total' => $stores->total(),
//            'per_page' => $stores->perPage(),
//            'current_page' => $stores->currentPage(),
//            'last_page' => $stores->lastPage(),
//            'from' => $stores->firstItem(),
//            'to' => $stores->lastItem(),
//        ];

    public function uploadImage($store, $logo)
    {
        if($store && $store->logo){
            Storage::disk('public')->delete($store->logo);
        }
        return $logo->store('stores', 'public');
    }

    public function showForUser(int $id, String $lang)
    {
        $store = Store::select('id','user_id', "name_{$lang} as name", "description_{$lang} as description", 'logo', 'is_featured','rating','num_of_rate')
            ->with([
                'address' => function($query) use ($lang){
                $query->select('id','store_id', "address_{$lang} as address", 'address_details', 'phone', 'lat', 'lon');
                 },
                'user.profile' => function($query) use ($lang){
                 $query->select('user_id','first_name', 'last_name');
                }
            ])->where('status','approved')->findOrFail($id);
        return [
            'id' => $store->id,
            'name' => $store->name,
            'description' => $store->description,
            'logo' => $this->imageService->getUrl($store->logo),
            'rating' => $store->rating,
            'num_of_rate' => $store->num_of_rate,
            'is_featured' => $store->is_featured,
            'address' => $store->address,
            'owner' => [
                'email' => $store->user->email,
                'name' => optional($store->user->profile)->first_name.''.optional($store->user->profile)->last_name
            ]

        ];
    }

    public function showForAdmin(int $id, String $lang)
    {
        $store = Store::select('id','user_id', "name_{$lang} as name", "description_{$lang} as description", 'logo', 'is_featured','rating','num_of_rate','status')
            ->with([
                'address' => function($query) use ($lang){
                    $query->select('id','store_id', "address_{$lang} as address", 'address_details', 'phone', 'lat', 'lon');
                },
                'user.profile' => function($query) use ($lang){
                    $query->select('user_id','first_name', 'last_name');
                }
            ])->findOrFail($id);
        return [
            'id' => $store->id,
            'name' => $store->name,
            'description' => $store->description,
            'logo' => $this->imageService->getUrl($store->logo),
            'rating' => $store->rating,
            'num_of_rate' => $store->num_of_rate,
            'status' => $store->status,
            'is_featured' => $store->is_featured,
            'address' => $store->address,
            'owner' => [
                'email' => $store->user->email,
                'name' => optional($store->user->profile)->first_name.''.optional($store->user->profile)->last_name
            ]

        ];
    }





    public function create(array $data)
    {
        $data['user_id'] = auth()->id();
        if(isset($data['logo'])){
            $data['logo'] = $this->imageService->upload($data['logo'], 'stores_logos');
        }
        return Store::create($data);
    }


    public function update(Store $store, array $data)
    {
        if(isset($data['logo'])){
            $data['logo'] = $this->imageService->replace($store, $data['logo']);
        }
        $store->update($data);
        return $store->fresh();
    }

    public function delete(Store $store)
    {
        $this->imageService->delete($store->logo);
        return $store->delete();
    }
    public function changeStatus(Store $store, string $status)
    {
        $store->update(['status' => $status]);
        return $store;
    }
    public function extractStoreData(Request $request)
    {
        return $request->only(['name_ar', 'name_en', 'description_ar', 'description_en', 'logo']);
    }

    public function extractAddressData(Request $request)
    {
        return $request->only(['lat', 'lon', 'address_details','phone']);
    }
}
