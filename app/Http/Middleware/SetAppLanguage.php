<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAppLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // بنقرأ اللغة من الهيدر
        $lang = $request->header('Accept-Language');

        // إذا اللغة مو ar أو en، بنختار en كافتراضي
        if (!in_array($lang, ['ar', 'en'])) {
            $lang = 'en';
        }

        // منخزنها بالطلب
        $request->merge(['lang' => $lang]);

        return $next($request);
    }

}
