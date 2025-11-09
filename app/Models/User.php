<?php

namespace App\Models;

use App\Enums\UserStatusEnums;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'status' => UserStatusEnums::class,
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
