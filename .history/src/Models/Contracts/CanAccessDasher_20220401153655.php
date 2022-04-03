<?php

declare(strict_types=1);

namespace Dasher\Models\Contracts;

interface CanAccessDasher
{
    public function canAccessDasher() : bool;
}
