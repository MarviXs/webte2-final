<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasUuids, Notifiable;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'owner_id');
    }
}
