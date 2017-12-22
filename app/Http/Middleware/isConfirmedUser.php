<?php

namespace App\Http\Middleware;

use Closure;

class isConfirmedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->confirmed_at === null) {
            return response()->json([
                'error' => true,
                'message' => 'You are not confirmed user. Please confirm your account with verification code that has sent you.'
            ]);
        }

        return $next($request);
    }
}
