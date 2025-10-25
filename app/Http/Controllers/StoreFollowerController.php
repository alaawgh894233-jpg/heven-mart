<?php

namespace App\Http\Controllers;

use App\Models\StoreUserFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreFollowerController extends Controller
{
    public function follow($StoreId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if (StoreUserFollower::where('user_id', $user->id)->where('store_id', $StoreId)->exists()) {
            return response()->json(['message' => 'Already followed'], 409);
        }
        StoreUserFollower::create([
            'user_id' => $user->id,
            'store_id' => $StoreId,
        ]);
        return response()->json(['message' => 'successfully followed the store']);
    }

    public function unfollow($StoreId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        StoreUserFollower::where('user_id', $user->id)->where('store_id', $StoreId)->delete();
        return response()->json(['message' => 'Successfully unfollowed the store.']);
    }


//    public function view(){
//        $user = auth()->id();
//        $StoreUserFollower = StoreUserFollower::where('user_id', $user)->get();
//        return response()->json(['message' => 'follow list',
//                $StoreUserFollower]
//            ,200);
//    }
}
