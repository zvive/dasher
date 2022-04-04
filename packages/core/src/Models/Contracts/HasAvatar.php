<?php

declare(strict_types=1);

namespace Dasher\Models\Contracts;

interface HasAvatar
{
    public function getDasherAvatar() : ?string;
}
