<?php

declare(strict_types=1);

namespace Dasher\Resources\Relationships;

use Dasher\Resources\Table;

class HasManyRelationships extends RelationManager
{
    use Concerns\CanAssociateRecords;
    use Concerns\CanCreateRecords;
    use Concerns\CanDeleteRecords;
    use Concerns\CanDissociateRecords;
    use Concerns\CanEditRecords;
    protected static string $view = 'dasher::resources.relation-managers.has-many-relation-manager';

    protected function getResourceTable() : Table
    {
        if ( ! $this->resourceTable) {
            $table = Table::make();

            $table->actions([
                $this->getEditAction(),
                $this->getDissociateAction(),
                $this->getDeleteAction(),
            ]);

            $table->bulkActions(\array_merge(
                ($this->canDeleteAny() ? [$this->getDeleteBulkAction()] : []),
                ($this->canDissociateAny() ? [$this->getDissociateBulkAction()] : []),
            ));

            $table->headerActions(\array_merge(
                ($this->canCreate() ? [$this->getCreateAction()] : []),
                ($this->canAssociate() ? [$this->getAssociateAction()] : []),
            ));

            $this->resourceTable = static::table($table);
        }

        return $this->resourceTable;
    }
}
