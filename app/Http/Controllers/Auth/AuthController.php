<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendOtpJob;
use App\Models\Cart;
use App\Models\User;
use App\Services\OtpService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $userService;
    protected $otpService;

    public function __construct(UserService $userService, OtpService $otpService)
    {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }

    // ================= REGISTER =================
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        try {

            $user = DB::transaction(function () use ($data) {

                $user = User::where('email', $data['email'])
                    ->lockForUpdate()
                    ->first();

                if ($user && $user->email_verified_at) {
                    throw new \Exception('already_registered');
                }

                if (!$user) {
                    $user = User::create($data);
                } else {
                    $user->update($data);
                }

                return $user;
            });

            $otp = $this->otpService->generateAndCache($user);

            SendOtpJob::dispatch($user->id, $otp);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully. OTP sent.',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {

            if ($e->getMessage() === 'already_registered') {
                return response()->json([
                    'message' => 'this account is already registered'
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    // ================= VERIFY OTP =================
    public function verifyByOtp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        return DB::transaction(function () use ($data) {

            $user = User::where('email', $data['email'])
                ->lockForUpdate()
                ->firstOrFail();

            if (!$this->otpService->isVerified($data['email'], $data['otp'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ]);
            }

            $user->update([
                'email_verified_at' => now()
            ]);

            Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['total_price' => 0, 'quantity' => 0]
            );

            return response()->json([
                'success' => true,
                'access_token' => $this->userService->createToken($user),
                'user' => $user
            ]);
        });
    }

    // ================= LOGIN =================
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!$this->userService->checkLogin($data['email'], $data['password'])) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong credentials'
            ]);
        }

        $user = $this->userService->findByEmail($data['email']);

        return response()->json([
            'success' => true,
            'access_token' => $this->userService->createToken($user),
            'user' => $user
        ]);
    }

    // ================= FORGET PASSWORD =================
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = $this->userService->findByEmail($request->email);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found'
            ], 400);
        }

        $otp = $this->otpService->generateAndCache($user);

        SendOtpJob::dispatch($user, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent'
        ]);
    }

    // ================= RESEND OTP =================
    public function resendOTP(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $key = 'otp_resend_' . $request->email;

        if (Cache::has($key)) {
            return response()->json([
                'success' => false,
                'message' => 'Wait before resending'
            ], 429);
        }

        Cache::put($key, true, now()->addSeconds(30));

        $user = $this->userService->findByEmail($request->email);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 400);
        }

        $otp = $this->otpService->generateAndCache($user);

        SendOtpJob::dispatch($user, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP resent'
        ]);
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out'
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

        $token = $this->userService->genarateAndCasheTokenForPassword($request->email);
        return response()->json([
            'success' => true ,
            'email' => $request->email ,
            'token' => $token ,
            'message' => 'set your password and token',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        return DB::transaction(function () use ($request) {

            $user = User::where('email', $request->email)
                ->lockForUpdate()
                ->firstOrFail();

            $isMatched = $this->userService
                ->checkTokenForPassword($request->email, $request->token);

            if (!$isMatched) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid token'
                ]);
            }

            $user->update([
                'password' => Hash::make($request->password),
                'email_verified_at' => now()
            ]);

            $token = $this->userService->createToken($user);

            return response()->json([
                'success' => true,
                'access_token' => $token,
                'user' => $user
            ]);
        });
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|confirmed|min:8',
        ]);
        $user = $request->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The old password is incorrect.'
            ], 400);
        }
        $this->userService->changePassword($user, $request->password);
        return response()->json([
            'success' => true,
            'message' => 'Your password has been changed.'
        ]);

    }

}
