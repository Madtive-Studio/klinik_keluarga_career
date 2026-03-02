<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user() || !$request->user()->email_verified_at) {
            return redirect()->route('client.login')->with('error', 'Email kamu belum di verifikasi');
        }

        return $next($request);
    }
}
