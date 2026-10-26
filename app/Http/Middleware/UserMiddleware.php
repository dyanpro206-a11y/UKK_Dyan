<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Pastikan hanya user biasa (bukan admin) yang dapat mengakses panel user.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'user') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Halaman tersebut hanya untuk user. Anda masuk sebagai admin.');
        }

        return $next($request);
    }
}
