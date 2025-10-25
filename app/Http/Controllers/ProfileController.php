<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function create_profile(CreateProfileRequest $request)
    {
        $user = Auth::user();

        $profile = $user->profiles;

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }

        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->gender = $request->gender;
        $profile->birth_day = $request->birth_day;

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture = $path;
        }

        $profile->save();

        return response()->json([
            'message' => 'Information created successfully',
            'User ' => $profile,
        ], 201);
    }

    public function update_Profile(UpdateProfileRequest $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->first();
        if (!$profile) {
            return response(['message' => 'Please create profile'], 404);
        }

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture = $imagePath;
        }

        $profile->update($request->validated());
        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile,
        ], 200);
    }

    public function show_profile()
    {
        $user = auth()->id();
        $profile = Profile::where('user_id', $user)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found',
            ], 404);
        }

        return response()->json([
            'your profile' => $profile,
        ], 200);
    }




}
