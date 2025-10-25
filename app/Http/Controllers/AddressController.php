<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Services\AddressService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }
    public function index(Request $request)
    {
        $lang = $request->header('accept-language');
        $lang = in_array($lang, ['ar', 'en']) ? $lang : 'en';
        $addresses = Address::select('id', 'name', "address_{$lang} as address", 'address_details','phone','type','is_default','lat','lon',)->where('user_id', Auth::id())->orderBy('is_default')->get();
        return response()->json($addresses);
    }

    public function show(Request $request, $id)
    {
        $lang = $request->header('accept-language');
        $lang = in_array($lang, ['ar', 'en']) ? $lang : 'en';
        $address = Address::select('id', 'name', "address_{$lang} as address", 'address_details','phone','type','is_default','lat','lon',)->where('user_id', Auth::id())->findOrFail($id);
        return response()->json($address);
    }

    public function store(CreateAddressRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $address = $this->addressService->createForUser($user, $data);
        return response()->json($address, 201);
    }

    public function update(UpdateAddressRequest $request, $id)
    {
        $data = $request->validated();
        $user = $request->user();
        $address = Address::findOrFail($id);
        $address = $this->addressService->updateForUser($address, $user, $data);
        return response()->json($address);
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return response()->json(null, 204);
    }
}
