<?php

declare(strict_types=1);

namespace Admin\Models\Contracts;

interface HasName
{
    public function getDasherName() : string;
}
