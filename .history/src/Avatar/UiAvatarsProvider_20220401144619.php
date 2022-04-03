<?php

declare(strict_types=1);

namespace Zvive\Dasher\Avatar;

use Admin\Facades\Dashboard;
use Illuminate\Database\Eloquent\Model;

class UiAvatarsProvider implements Contracts\AvatarProvider
{
    public function get(Model $user) : string
    {
        return 'https://ui-avatars.com/api/?name='.\urlencode(Dashboard::getUserName($user)).'&color=FFFFFF&background=111827';
    }
}
