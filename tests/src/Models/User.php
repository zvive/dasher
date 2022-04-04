<?php

declare(strict_types=1);

namespace Dasher\Tests\Models;

use Dasher\Models\Contracts\DasherUser;
use Dasher\Tests\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements DasherUser
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden  = [
        'password',
        'remember_token',
    ];

    public function canAccessDasher() : bool
    {
        return true;
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
