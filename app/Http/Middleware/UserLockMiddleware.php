<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserLockMiddleware
{
    public function handle($request, Closure $next)
    {
        $userId = auth()->id();

        return Cache::lock("order_lock_{$userId}", 60)
            ->block(5, function () use ($request, $next, $userId) {

                Log::info('ORDER_LOCK_ACQUIRED', [
                    'user_id' => $userId,
                    'request_id' => $request->requestId ?? null,
                ]);

                return $next($request);
            });
    }
}
