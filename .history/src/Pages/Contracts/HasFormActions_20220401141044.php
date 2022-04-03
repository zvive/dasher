<?php

namespace Adminin\Pages\Contracts;

use Admin\Pages\Actions\Action;

interface HasFormActions
{
    public function getCachedFormAction(string $name): ?Action;
}
