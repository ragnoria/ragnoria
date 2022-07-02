<?php

namespace App\Http\Controllers;

use App\Facades\Auth;
use App\Http\Middleware\Aleta;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Account;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

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

        $cookie = Aleta::preserve(Auth::account());

        return (new Response())
            ->setContent(['message' => 'ok'])
            ->setStatusCode(Response::HTTP_OK)
            ->withCookie($cookie);
    }

    public function register(RegisterRequest $request): Response
    {
        Account::create([
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
        $cookie = Aleta::forget(Auth::account());
        Auth::logout();

        return (new Response())
            ->setContent(['message' => 'ok'])
            ->setStatusCode(Response::HTTP_OK)
            ->withCookie($cookie);
    }

}
