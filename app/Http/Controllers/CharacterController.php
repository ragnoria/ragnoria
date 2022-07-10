<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Facades\Auth;
use App\Http\Requests\CharacterCreateRequest;
use App\Http\Resources\AccountResource;
use App\Models\Character;
use Illuminate\Http\Response;

class CharacterController extends Controller
{
    public function create(CharacterCreateRequest $request): Response
    {
        $character = new Character;
        $character->account_id = Auth::account()->id;
        $character->name = $request->get('name');
        $character->role = Role::PLAYER;
        $character->save();

        return (new Response())
            ->setContent(new AccountResource(Auth::account()))
            ->setStatusCode(Response::HTTP_OK);
    }
}
