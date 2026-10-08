<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Kasir
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'kasir' || Auth::user()->role == 'admin') {
                return $next($request);
            } else {
                abort(403, 'Akses ditolak! Anda tidak memiliki akses Kasir.');
            }
        } else {
            return redirect()->route('login');
        }
    }
}