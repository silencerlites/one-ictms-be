<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenVersion
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        $claim = auth('api')->payload()->get('token_version');

        if (! $user || ! is_numeric($claim) || (int) $claim !== (int) $user->token_version) {
            return response()->json(['success' => false, 'message' => 'Token has been revoked.'], 401);
        }

        return $next($request);
    }
}
