<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /*
     * find user by id
     *
     *
     * @param int $id
     * @return User else
     * @throws ModelNotFoundExceptoin
     * */
    public function findById(int $id) : User
    {
        return User::findOrFail($id);
    }

    /* find user by email
     *
     * @param string $email
     * @param return User else
     * @param return null
     *
     * */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    /*
     * create a new user
     * @param array contain email and hash password
     *
     * */
    public function createUser(array $data) : User
    {
        return $user = User::create($data);
    }

    /*
     *
     * */
    public function updateUser(User $user, array $data) : User
    {
        $user->update($data);
        $user->save();
        return $user;
    }

    public function createToken(User $user) : string
    {
        return $user->createToken('authToken')->plainTextToken;
    }

    public function checkLogin(string $email, string $password) : bool
    {
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            $user = $this->findByEmail($email);
            if($user->email_verified_at != null){
                return true;
            }
            return false;
        }
        return false;
    }

    public function genarateAndCasheTokenForPassword(string $email) : string
    {
        $token = Str::random(20);
        Cache::put('token'.$email,$token,now()->addMinutes(5));
        return $token;
    }

    public function checkTokenForPassword(string $email, string $token) : bool
    {
        $tokenCache = Cache::get('token'.$email);
        if($tokenCache == $token){
            Cache::forget('token'.$email);
            return true;
        }
        return false;
    }

    public function changePassword(User $user, string $password) : bool
    {
        $user->update(['password' => Hash::make($password)]);
        $user->save();
        return true;
    }

    public function changeRole(User $user, string $role) : User
    {
        $user->update(['role' => $role]);
        $user->save();
        return $user;
    }
}
