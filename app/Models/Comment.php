<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $visible = [
        'id',
        'text',
        'rating',
        'parent_id',
        'created_at',
        'author',
    ];

    protected $fillable = [
        'text',
        'rating',
        'parent_id',
        'user_id',
    ];

    protected $appends = [
        'author',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAuthorAttribute()
    {
        return $this->user->name ?? 'Гость';
    }
}
