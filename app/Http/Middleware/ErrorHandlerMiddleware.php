<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class ErrorHandlerMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);

        } catch (\Throwable $e) {

            Log::error('APP_ERROR', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Server Error'
            ], 500);
        }
    }
}
