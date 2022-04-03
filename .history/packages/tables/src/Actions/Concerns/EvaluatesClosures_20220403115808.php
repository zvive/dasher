<?php

namespace Dasher\Tables\Actions\Concerns;

use Closure;
use Dasher\Tables\Actions\Action;
use Dasher\Tables\Actions\BulkAction;

trait EvaluatesClosures
{
    public function evaluate($value, array $parameters = [])
    {
        if ($value instanceof Closure) {
            return app()->call(
                $value,
                array_merge($this->getDefaultEvaluationParameters(), $parameters),
            );
        }

        return $value;
    }

    protected function getDefaultEvaluationParameters(): array
    {
        return array_merge(
            [
                'action' => $this,
                'livewire' => $this->getLivewire(),
            ],
            ($this instanceof Action ? ['record' => $this->getRecord()] : []),
            ($this instanceof BulkAction ? ['records' => $this->getRecords()] : []),
        );
    }
}
