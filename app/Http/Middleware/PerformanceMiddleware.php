<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class PerformanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $time = round(
            (microtime(true) - $start) * 1000,
            2
        );

        Log::info('PERFORMANCE', [
            'path' => $request->path(),
            'time_ms' => $time
        ]);

        return $response;
    }
}
