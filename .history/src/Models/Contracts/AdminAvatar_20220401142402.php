<?php

declare(strict_types=1);

namespace Admin\Models\Contracts;

interface AdminAvatar
{
    public function getAvatarForAdmin() : ?string;
}
