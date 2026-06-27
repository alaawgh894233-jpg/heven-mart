<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
class LoggingAspect
{
    public function handle($request, Closure $next)
    {
        $requestId = uniqid('REQ_');
        $start = microtime(true);
        Log::info('REQUEST_START', [
            'request_id' => $requestId,
            'url' => $request->path(),
            'method' => $request->method(),
            'user_id' => auth()->id()]);
        $response = $next($request);
        $time = round((microtime(true) - $start) * 1000, 2);
        Log::info('REQUEST_END', [
            'request_id' => $requestId,
            'execution_time_ms' => $time,
            'handled_by_port' => $_SERVER['SERVER_PORT'],
            'server_memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
        ]);
        return $response;
    }
}
