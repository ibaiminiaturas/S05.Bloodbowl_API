<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRevokedToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->token()->revoked) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return $next($request);
    }
}
