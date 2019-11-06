<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $visible = [
        'id',
        'name',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
    ];

    public function hasRole(string $role)
    {
        return $this->roles !== null && in_array($role, $this->roles);
    }
}
