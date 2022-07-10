<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
        ];
    }
}
