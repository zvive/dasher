<?php

namespace Dasher\Tables\Concerns;

use Closure;

trait HasRecordUrl
{
    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}
