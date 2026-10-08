<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Jika user belum login ATAU role user tidak sama dengan parameter
        if (!$user || $user->role !== $role) {
            abort(403, 'Akses ditolak! Anda tidak memiliki izin sebagai ' . $role);
        }

        return $next($request);
    }
}