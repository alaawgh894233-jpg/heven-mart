<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LoggingAspect
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);

        Log::info('REQUEST_START', [
            'url' => $request->path(),
            'method' => $request->method(),
            'user_id' => auth()->id()
        ]);

        $response = $next($request);

        $time = round(
            (microtime(true) - $start) * 1000,
            2
        );

        Log::info('REQUEST_END', [
            'url' => $request->path(),
            'execution_time_ms' => $time
        ]);

        return $response;
    }
}
