<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! (bool) $request->session()->get('preventia_admin', false)) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}