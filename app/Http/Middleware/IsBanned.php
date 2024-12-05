<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

/**
 * Еще один простой миддлварь, для демонстрации priority()
 */
class IsBanned
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var ?User $user */
        $user = $request->user();
        Assert::notNull($user);

        if ($user->isBanned()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
