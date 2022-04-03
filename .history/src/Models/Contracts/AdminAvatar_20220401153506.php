<?php

declare(strict_types=1);

namespace Dasher\Models\Contracts;

interface AdminAvatar
{
    public function getAvatarForAdmin() : ?string;
}
