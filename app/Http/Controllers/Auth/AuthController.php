<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\VerifyEmail;
use App\Models\Cart;
use App\Models\Password_reset;
use App\Models\User;
use App\Services\OtpService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $userService, $otpService;
    public function __construct(UserService $userService, OtpService $otpService)
    {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }
    public function register(RegisterRequest $request)
    {
        //validated data
        $validatedData = $request->validated();
        // find user by email
        $user = $this->userService->findByEmail($validatedData['email']);
        // if user register before
        if($user && $user->email_verified_at){
            return response()->json([
                'message' => 'this account is already registered, you can login now.'
                ],409);
        }
        //if user not exist before
        $validatedData['password'] = Hash::make($validatedData['password']);
        if(!$user){
            $user = $this->userService->createUser($validatedData);
        }else{
            $user = $this->userService->updateUser($user, $validatedData);
        }

        $otp = $this->otpService->generateAndCache($user);
        $this->otpService->sendOtp($user, $otp);

        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.',
            'email' => $user->email
        ]);
    }

    public function verifyByOtp(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'otp' => 'required|numeric|digits:6'
        ]);

        $isVerified = $this->otpService->isVerified($validatedData['email'], $validatedData['otp']);
        if(!$isVerified){
            return response()->json([
                'success' => false,
                'message' => 'The verification code is invalid.'
            ]);
        }
        $data['email_verified_at'] = now();
        $user = $this->userService->findByEmail($validatedData['email']);
        $user = $this->userService->updateUser($user, $data);
        $accessToken = $this->userService->createToken($user);
        Cart::create([
            'user_id' => $user->id,
            'total_price' => 0,
            'quantity' => 0
        ]);
        return response()->json([
            'success' => true,
            'access_token' => $accessToken,
            'user' => $user,
        ]);

    }

    public function login(LoginRequest $request){
        $validatedData = $request->validated();
        $isLogin = $this->userService->checkLogin($validatedData['email'], $validatedData['password']);
        if(!$isLogin){
            return response()->json([
                'success' => false,
                'message' => 'The email or password is incorrect.'
            ]);
        }
        $user = $this->userService->findByEmail($validatedData['email']);
        $accessToken = $this->userService->createToken($user);
        return response()->json([
            'success' => true,
            'access_token' => $accessToken,
            'user' => $user,
            ]);
    }
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);
        $user = $this->userService->findByEmail($request->email);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'This email does not exist.',
            ],400);
        }
        $otp = $this->otpService->generateAndCache($user);
        $this->otpService->sendOtp($user, $otp);
        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.',
        ]);
    }

    public function resendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);
        $user = $this->userService->findByEmail($request->email);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'This email does not exist.',
            ],400);
        }
        $otp = $this->otpService->generateAndCache($user);
        $this->otpService->sendOtp($user, $otp);
        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.',
        ]);

    }
    public function  verifyPassword(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'otp' => 'required|numeric|digits:6'
        ]);

        $isVerified = $this->otpService->isVerified($validatedData['email'], $validatedData['otp']);
        if(!$isVerified){
            return response()->json([
                'success' => false,
                'message' => 'The verification code is invalid.'
            ]);
        }
//        $data['email_verified_at'] = now();
//        $user = $this->userService->findByEmail($validatedData['email']);
//        $user = $this->userService->updateUser($user, $data);
        $token = $this->userService->genarateAndCasheTokenForPassword($request->email);
        return response()->json([
            'success' => true ,
            'email' => $request->email ,
            'token' => $token ,
            'message' => 'set your password and token',
        ]);
    }

    public function  resetPassword(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);
        $isMatched = $this->userService->checkTokenForPassword($request->email, $request->token);
        if(!$isMatched){
            return response()->json([
                'success' => false,
                'message' => 'This password reset token is invalid.'
            ]);
        }
        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = now();
        $user = $this->userService->findByEmail($request->email);
        $user = $this->userService->updateUser($user, $data);
        $accessToken = $this->userService->createToken($user);
        return response()->json([
            'success' => true,
            'access_token' => $accessToken,
            'user' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|confirmed|min:8',
        ]);
        $user = $request->user();
        if(!Hash::check($request->old_password, $user->password)){
            return response()->json([
                'success' => false,
                'message' => 'The old password is incorrect.'
            ],400);
        }
        $this->userService->changePassword($user, $request->password);
        return response()->json([
            'success' => true,
            'message' => 'Your password has been changed.'
        ]);


    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
