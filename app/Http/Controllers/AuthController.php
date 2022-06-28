<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    public function check(): Response
    {
        return (new Response())
            ->setContent(['status' => Auth::check()])
            ->setStatusCode(Response::HTTP_OK);
    }

    public function login(LoginRequest $request): Response
    {
        if (Auth::check()) {
            return (new Response())
                ->setContent(['message' => 'already logged in'])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            return (new Response())
                ->setContent(['message' => 'email or password is incorrect'])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $token = Auth::user()->createToken(config('auth.api.token_name'));
        $token->accessToken->forceFill(['last_used_at' => now()])->save();
        Auth::user()->withAccessToken($token);

        return (new Response())
            ->setContent(['message' => 'ok'])
            ->setStatusCode(Response::HTTP_OK)
            ->withCookie($this->getAuthCookie());
    }

    public function register(RegisterRequest $request): Response
    {
        User::create([
            'name' => current(explode('@', $request->get('email'))),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        return (new Response())
            ->setContent(['message' => 'ok'])
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function logout(): Response
    {
        Auth::user()->currentAccessToken()->delete();
        Auth::logout();

        return (new Response())
            ->setContent(['message' => 'ok'])
            ->setStatusCode(Response::HTTP_OK)
            ->withCookie($this->getAuthCookie());
    }

    private function getAuthCookie(): Cookie
    {
        $token = Auth::user()?->currentAccessToken()?->plainTextToken;

        return new Cookie(
            name: config('auth.api.cookie.name'),
            value: $token ?? null,
            expire: $token ? 0 : time(),
            domain: config('auth.api.cookie.domain'),
        );
    }

}
