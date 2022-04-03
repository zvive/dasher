<?php

declare(strict_types=1);

namespace Admin\Models\Contracts;

interface AdminName
{
    public function getNameForAdmin() : string;
}
