<?php

namespace App\Facades;

use App\Models\Account;

class Auth extends \Illuminate\Support\Facades\Auth
{
    public static function account(): ?Account
    {
        $user = self::user();
        if ($user instanceof Account) {
            return $user;
        }

        return null;
    }
}
