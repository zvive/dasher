<?php

declare(strict_types=1);

namespace Dasher\AvatarProviders\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AvatarProvider
{
    public function get(Model $user) : string;
}
