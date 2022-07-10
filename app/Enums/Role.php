<?php

namespace App\Enums;

enum Role: string
{
    case PLAYER = 'player';
    case GAMEMASTER = 'gamemaster';
}
