<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $account_id
 * @property string $name
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
