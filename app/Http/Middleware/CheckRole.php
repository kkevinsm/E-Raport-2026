<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar VS Code pintar

class CheckRole
{
    // Tambahkan kata 'string' sebelum ...$roles
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // Gunakan Auth::check() alih-alih auth()->check()
        if (!Auth::check()) {
            return redirect('/');
        }

        foreach ($roles as $role) {
            // Gunakan Auth::user() alih-alih auth()->user()
            if (Auth::user()->role === $role) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}