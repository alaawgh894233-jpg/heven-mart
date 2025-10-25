<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function add($productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if (Favorite::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Already in favorites'], 409);
        }
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);
        return response()->json(['message' => 'Added to favorites']);
    }

    public function remove($productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        Favorite::where('user_id', $user->id)->where('product_id', $productId)->delete();
        return response()->json(['message' => 'Removed from favorites']);
    }


    public function view(){
        $user = auth()->id();
        $Favorite = Favorite::where('user_id', $user)->get();
        return response()->json(['message' => 'Products in favorites',
                $Favorite   ]
            ,200);
    }
}
