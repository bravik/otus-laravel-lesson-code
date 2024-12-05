<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Простой мидллвар прерывающий запрос в случае если не аутентифицирован пользователь
 */
class Authenticated
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
