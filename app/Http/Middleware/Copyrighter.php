<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

/**
 * Миддлварь исполняющийся после обработки запроса.
 * Добавляет заголовок с копирайтом к запросу
 */
class Copyrighter
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->add([
            'X-Copyright-Notice' => "© Roman Naumenko " . date('Y')
        ]);

        return $response;
    }
}
