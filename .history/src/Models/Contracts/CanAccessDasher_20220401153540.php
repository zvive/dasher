<?php

declare(strict_types=1);

namespace Dasher\Models\Contracts;

interface AdminGate
{
    public function canAccessDasher() : bool;
}
