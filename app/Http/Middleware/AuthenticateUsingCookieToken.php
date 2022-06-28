<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateUsingCookieToken
{
    public function handle(Request $request, \Closure $next)
    {
        if ($cookie = $request->cookie(config('auth.api.cookie.name'))) {
            if ($token = PersonalAccessToken::find($cookie)) {
                if ($user = $token->tokenable()->first()) {
                    $token->forceFill(['last_used_at' => now()])->save();
                    $user->withAccessToken($token);
                    Auth::login($user);
                }
            }
        }

        return $next($request);
    }
}
