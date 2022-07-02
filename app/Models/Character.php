<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Character extends Model
{
    use HasFactory;
}
