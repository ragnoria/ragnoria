<?php

namespace App\Services;

use App\Http\Middleware\Aleta;
use Ratchet\WebSocket\WsConnection;

class WsConnectionService
{
    public static function auth(WsConnection $conn): bool
    {
        $cookie = static::getCookie($conn, config('aleta.cookie.name'));
        if (!$account = Aleta::recognize($cookie)) {
            return false;
        }
        $conn->account = $account;

        return true;
    }

    public static function getCookie(WsConnection $conn, string $name): ?string
    {
        $cookies = current($conn->httpRequest->getHeader('Cookie'));
        $cookies = explode(';', $cookies);

        foreach ($cookies as $cookie) {
            $cookie = trim($cookie);
            [$_name, $value] = explode("=", $cookie);
            if ($_name == $name) {
                return $value;
            }
        }

        return null;
    }

}
