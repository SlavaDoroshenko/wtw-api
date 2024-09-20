<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $visible = [
        'name',
        'max_users'
    ];

    protected $fillable = [
        'name',
        'password',
        'max_users'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }
}
