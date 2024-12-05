<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Простейшая реализация проверки CSRF токена
 */
class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() === 'POST' || $request->method() === 'PUT') {
            $token = $request->input('_token');

            if (!$token || !$request->session()->token() || !hash_equals($request->session()->token(), $token)) {
                throw new TokenMismatchException('CSRF token mismatch.');
            }
        }

        return $next($request);
    }
}
