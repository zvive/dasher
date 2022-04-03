<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

trait HasRelations
{
    public $activeRelationship = null;

    protected function getRelationships() : array
    {
        $managers = $this->getResource()::getRelationships();

        return \array_filter(
            $managers,
            fn (string $manager) : bool => $manager::canViewForRecord($this->record),
        );
    }

    public function mountHasRelationships() : void
    {
        $managers = $this->getRelationships();

        if (\array_key_exists($this->activeRelationship, $managers)) {
            return;
        }

        $this->activeRelationship = \array_key_first($this->getRelationships()) ?? null;
    }
}
