<?php

declare(strict_types=1);

namespace Dasher\Models\Contracts;

interface HasName
{
    public function getDasherName() : string;
}
