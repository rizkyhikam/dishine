<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Arahkan user yang sudah login sesuai role-nya
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'reseller') {
                return redirect('/reseller/dashboard');
            } else {
                return redirect('/pelanggan/dashboard');
            }
        }

        return $next($request);
    }
}
