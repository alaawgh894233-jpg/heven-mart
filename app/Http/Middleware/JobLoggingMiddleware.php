<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JobLoggingMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        if (!isset($request->requestId)) {
            $request->requestId = (string) Str::uuid();
        }
        $userId = auth()->id();
        Log::info('ORDER_JOB_START', [
            'user_id' => $userId,
            'request_id' => $request->requestId,
        ]);
        try {
            $result = $next($request);
            Log::info('ORDER_JOB_END', [
                'user_id' => $userId,
                'request_id' => $request->requestId,
                'status' => 'success',
                'time_ms' => round((microtime(true) - $start) * 1000, 2),
            ]);

            return $result;

        } catch (\Throwable $e) {

            Log::error('ORDER_JOB_FAILED', [
                'user_id' => $userId,
                'request_id' => $request->requestId,
                'message' => $e->getMessage(),
                'time_ms' => round((microtime(true) - $start) * 1000, 2),
            ]);

            throw $e;
        }
    }
}
