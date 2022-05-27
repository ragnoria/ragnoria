<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return ['message' => 'it works'];
});

Route::get('/login', function (Request $request) {
    $response = new Illuminate\Http\Response('Hello World');
    $response->withCookie(
        cookie(
            name: 'ragnoria-session',
            value: 'test',
            domain: '.ragnoria.localhost'
        )
    );

    return $response;
});
