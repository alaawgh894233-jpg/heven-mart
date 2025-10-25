<?php

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateAndCache(User $user, int $minutes = 5)
    {
        $otp = rand(100000, 999999);
        Cache::put('otp'.$user->email, $otp, now()->addMinutes($minutes));
        return $otp;
    }

    public function isVerified(string $email, int $otp): bool
    {
        $cached = Cache::get('otp'.$email);
        if($cached != $otp) {
            return false;
        }
        Cache::forget('otp'.$email);
        return true;
    }

    public function sendOtp(User $user, $otp)
    {
        Mail::to($user->email)->send(new VerifyEmail($otp));
        return true;
    }


}
