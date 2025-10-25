<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Services\AddressService;
use App\Services\ImageService;
use App\Services\StoreService;
use App\Services\UserService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $storeService, $userService, $addressService, $imageService;
    public function __construct(StoreService $storeService, UserService $userService, AddressService $addressService, ImageService $imageService)
    {
        $this->storeService = $storeService;
        $this->userService = $userService;
        $this->addressService = $addressService;
        $this->imageService = $imageService;
    }

    public function getAllForUser(Request $request)
    {
        $lang = $request->header('Accept_Language','en');
        $per_page = $request->input('per_page',10);
        $page = $request->input('page',1);
        $stores = $this->storeService->getAllForUser($per_page,$page, $request->all());
        $data = StoreResource::collection($stores)->resolve();
        $meta = $this->paginationMeta($stores);
        return response()->json([
            'data' => $data,
            'meta' => $meta
        ]);
    }

    public function getAllForAdmin(Request $request)
    {
        $lang = $request->header('Accept_Language');
        $lang = in_array($lang, ['en', 'ar']) ? $lang : 'en';
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $filters = $request->all();
        $stores = $this->storeService->getAllForAdmin($per_page, $page, $filters);
        $stores = StoreResource::collection($stores);
        $data = $stores->resolve();
        return response()->json([
            'data' => $data,
            'meta' => $this->paginationMeta($stores),
        ]);
}
    public function create(CreateStoreRequest $request)
    {
        $user = auth()->user();
        $store = $this->storeService->findByUser($user);
        if ($store) {
            return response()->json([
                'success' => false,
                'message' => 'this user already have store'
            ],409);
        }

        $store = $this->storeService->create($this->storeService->extractStoreData($request));
        $address = $this->addressService->createForStore($store, $this->storeService->extractAddressData($request));
        return response()->json([
            'success' => true,
            'message' => 'your store is pending admin approval',
            'store' => $store,
            'address' => $address
        ],202);
    }

    public function update(UpdateStoreRequest $request, $id)
    {
        $store = $this->storeService->findById($id);
        $user = auth()->user();
        if($store->user_id != $user->id && $user->role != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'forbidden'
            ],403);
        }
        $this->storeService->update($store, $this->storeService->extractStoreData($request));
        $address = $this->addressService->updateForStore($store, $this->storeService->extractAddressData($request));
        return response()->json([
            'success' => true,
            'message' => 'your store is updated',
            'store' => $store,
            'address' => $address
        ], 200);
    }

    public function showForUser(Request $request, int $id)
    {
        $lang = $request->header('Accept_Language');
        $lang = in_array($lang, ['en','ar']) ? $lang : 'en';
        return response()->json([
            'success' => true,
            'store' => $this->storeService->showForUser($id, $lang),
        ],200);
    }

    public function showForAdmin(Request $request, int $id)
    {
        $lang = $request->header('Accept_Language');
        $lang = in_array($lang, ['en','ar']) ? $lang : 'en';
        return response()->json([
            'success' => true,
            'store' => $this->storeService->showForAdmin($id, $lang),
        ],200);
    }

    public function myStore(Request $request)
    {
        $lang = $request->header('Accept_Language');
        $lang = in_array($lang, ['en','ar']) ? $lang : 'en';
        $user = auth()->user();
        $store  = Store::with([
            'address'
        ])->where('user_id' ,$user->id)->firstOrFail();
        return response()->json([
            'success' => true,
            'store' => $store
        ],200);
    }

    public function approve(int $id)
    {
        // check if role user == admin
         $store = $this->storeService->findById($id);
        $store = $this->storeService->changeStatus( $store,'approved');
        $user = $store->user;
        $user = $this->userService->changeRole($user,'seller');
        //notify for seller and change role to seller
        return response()->json([
            'success' => true,
            'message' => 'this store is approved',
            'user' => $user,
            'store' => $store
        ]);
    }

    public function suspend(Request $request, int $id)
    {
        $request->validate(['reason'=>'required|string']);
        $message = $request->input('reason');
        //check if user admin
        $store = $this->storeService->findById($id);
        $store = $this->storeService->changeStatus( $store,'suspend');
        $user = $this->userService->changeRole($store->user()->first(),'customer');
        //  seller
        return response()->json([
            'success' => true,
            'message' => 'this store is rejected'
        ]);
    }

    private function paginationMeta($paginator)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }
}
