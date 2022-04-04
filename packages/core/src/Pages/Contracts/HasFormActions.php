<?php

declare(strict_types=1);

namespace Dasher\Pages\Contracts;

use Dasher\Pages\Actions\Action;

interface HasFormActions
{
    public function getCachedFormAction(string $name) : ?Action;
}
