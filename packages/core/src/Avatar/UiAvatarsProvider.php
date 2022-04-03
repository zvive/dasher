<?php

declare(strict_types=1);

namespace Dasher\Avatar;

use Dasher\Facades\Dasher;
use Illuminate\Database\Eloquent\Model;

class UiAvatarsProvider implements Contracts\AvatarProvider
{
    public function get(Model $user) : string
    {
        return 'https://ui-avatars.com/api/?name='.\urlencode(Dasher::getUserName($user)).'&color=FFFFFF&background=111827';
    }
}
