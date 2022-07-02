<?php

namespace App\Http\Middleware;

use App\Facades\Auth;
use App\Models\Account;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Cookie;

class Aleta
{
    public static function preserve(Account $account): Cookie
    {
        $token = $account->createToken(config('aleta.token_name'));
        $token->accessToken->forceFill(['last_used_at' => time()])->save();
        $account->withAccessToken($token);

        return new Cookie(
            name: config('aleta.cookie.name'),
            value: $token->plainTextToken,
            expire: 0,
            domain: config('aleta.cookie.domain'),
        );
    }

    public static function forget(Account $account): Cookie
    {
        $account->currentAccessToken()->delete();

        return new Cookie(
            name: config('aleta.cookie.name'),
            value: null,
            expire: time(),
            domain: config('aleta.cookie.domain'),
        );
    }

    public static function recognize(string $cookie): ?Account
    {
        if (!$token = PersonalAccessToken::find($cookie)) {
            return null;
        }
        if (!$account = $token->tokenable()->first()) {
            return null;
        }
        $token->forceFill(['last_used_at' => now()])->save();
        $account->withAccessToken($token);

        return $account;
    }

    public function handle(Request $request, \Closure $next)
    {
        if ($cookie = $request->cookie(config('aleta.cookie.name'))) {
            if ($account = static::recognize($cookie)) {
                Auth::login($account);
            }
        }

        return $next($request);
    }
}
