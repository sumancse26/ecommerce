<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $decodedToken = JWTToken::decodeToken($token);
        if ($decodedToken == 'unauthorized') {
            return redirect('/loginPage');
        }
        $request->headers->set('userEmail', $decodedToken->email);
        $request->headers->set('userId', $decodedToken->userId);
        return $next($request);
    }
}
