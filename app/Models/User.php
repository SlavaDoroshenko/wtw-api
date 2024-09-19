<?php

namespace App\Models;

// use фыыыффффффффффффццц;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_USER = 0;
    public const ROLE_MODERATOR = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $visible = [
        'name',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


    public function isModerator()
    {
        return $this->role_id === self::ROLE_MODERATOR;
    }

    public function hasFilm(Film $film): bool
    {
        return $this->films()->where('film_id', $film->id)->exists();
    }
}
