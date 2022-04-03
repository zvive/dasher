<?php

declare(strict_types=1);

namespace Dasher\Models\Contracts;

interface DasherUser
{
    public function canAccessDasher() : bool;
}
