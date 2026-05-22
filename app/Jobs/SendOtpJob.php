<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = [5, 10, 30];

    public $userId;
    public $otp;

    public function __construct($userId, $otp)
    {
        $this->userId = $userId;
        $this->otp = $otp;
    }

    public function handle(OtpService $otpService)
    {
        $user = User::find($this->userId);

        if (!$user) return;

        $otpService->sendOtp($user, $this->otp);
    }
}
