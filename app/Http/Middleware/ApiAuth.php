<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { {
            $token = $request->header('Authorization');
            if ($token == null) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $decodedToken = JWTToken::decodeToken($token);
            $request->headers->set('userEmail', $decodedToken->email);
            $request->headers->set('userId', $decodedToken->userId);
            return $next($request);
        }
    }
}
