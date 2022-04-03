<?php

declare(strict_types=1);

namespace Admin\Models\Contracts;

interface AdminGate
{
    public function canAccessAdmin() : bool;
}
