<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Ensure the authenticated user is marked as active.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_active) {
            optional($user->currentAccessToken())->delete();

            abort(Response::HTTP_FORBIDDEN, __('Akun Anda tidak aktif.'));
        }

        return $next($request);
    }
}

