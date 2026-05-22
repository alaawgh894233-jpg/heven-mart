<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class AopLoggingMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        Log::info("AOP_REQUEST_TIME", [
            'path' => $request->path(),
            'method' => $request->method(),
            'time_ms' => (microtime(true) - $start) * 1000
        ]);

        return $response;
    }
}
