<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Пример миддлвара с DI и принимающего параметры
 */
class AuthorizeCanAnyMiddleware
{
    public function __construct(
        private Gate $gate,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (!$this->gate->any($permissions)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
