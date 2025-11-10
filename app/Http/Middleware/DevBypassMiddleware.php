<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DevBypassMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kalau DEV_MODE aktif, anggap user login sebagai admin default
        if (env('DEV_MODE', false)) {
            // Simulasikan user login (bisa pilih role sesuai kebutuhan FE)
            auth()->loginUsingId(1); // pastikan user ID 1 ada di DB kamu
        }

        return $next($request);
    }
}
