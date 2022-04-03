<?php

namespace Dasher\Pages\Concerns;

use Filament\Pages\Actions\Action;

trait HasFormActions
{
    protected ?array $cachedFormActions = null;

    protected function getCachedFormActions(): array
    {
        if ($this->cachedFormActions === null) {
            $this->cacheFormActions();
        }

        return $this->cachedFormActions;
    }

    protected function cacheFormActions(): void
    {
        $this->cachedFormActions = collect($this->getFormActions())
            ->mapWithKeys(function (Action $action): array {
                $action->livewire($this);

                return [$action->getName() => $action];
            })
            ->toArray();
    }

    public function getCachedFormAction(string $name): ?Action
    {
        return $this->getCachedFormActions()[$name] ?? null;
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
