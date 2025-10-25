<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return response()->json([
//        'message' => 'salam alikum  '
//    ]);
//});



Route::get('/', function () {
    return view('attributes');
});
