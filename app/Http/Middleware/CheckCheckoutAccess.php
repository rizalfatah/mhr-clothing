<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCheckoutAccess
{
    /**
     * Handle an incoming request.
     *
     * Allows checkout access for:
     * - Guest users (not authenticated)
     * - Authenticated users with verified email
     *
     * Blocks:
     * - Authenticated users without verified email
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Guest users are allowed to checkout
        if (!Auth::check()) {
            return $next($request);
        }

        // Authenticated users with verified email are allowed
        if (Auth::user()->hasVerifiedEmail()) {
            return $next($request);
        }

        // Authenticated users without verified email are redirected
        return redirect()
            ->route('verification.notice')
            ->with('error', 'Anda harus memverifikasi email terlebih dahulu sebelum melakukan checkout.');
    }
}
