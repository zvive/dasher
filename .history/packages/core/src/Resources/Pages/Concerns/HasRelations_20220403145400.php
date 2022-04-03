<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

trait HasRelations
{
    public $activeRelationManager = null;

    protected function getRelations() : array
    {
        $managers = $this->getResource()::getRelations();

        return \array_filter(
            $managers,
            fn (string $manager) : bool => $manager::canViewForRecord($this->record),
        );
    }

    public function mountHasRelations() : void
    {
        $managers = $this->getRelations();

        if (\array_key_exists($this->activeRelationManager, $managers)) {
            return;
        }

        $this->activeRelationManager = \array_key_first($this->getRelations()) ?? null;
    }
}
