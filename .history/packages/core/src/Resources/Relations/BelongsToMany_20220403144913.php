<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations;

use Dasher\Resources\Table;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToMany extends RelationManager
{
    use Concerns\CanAttachRecords;
    use Concerns\CanCreateRecords;
    use Concerns\CanDeleteRecords;
    use Concerns\CanDetachRecords;
    use Concerns\CanEditRecords;
    protected static string $view = 'dasher::resources.relation-managers.belongs-to-many-relation-manager';

    protected function getResourceTable() : Table
    {
        if ( ! $this->resourceTable) {
            $table = Table::make();

            $table->actions([
                $this->getEditAction(),
                $this->getDetachAction(),
                $this->getDeleteAction(),
            ]);

            $table->bulkActions(\array_merge(
                ($this->canDeleteAny() ? [$this->getDeleteBulkAction()] : []),
                ($this->canDetachAny() ? [$this->getDetachBulkAction()] : []),
            ));

            $table->headerActions(\array_merge(
                ($this->canCreate() ? [$this->getCreateAction()] : []),
                ($this->canAttach() ? [$this->getAttachAction()] : []),
            ));

            $this->resourceTable = static::table($table);
        }

        return $this->resourceTable;
    }

    // https://github.com/laravel/framework/issues/4962
    protected function getTableQuery() : Builder
    {
        $query = parent::getTableQuery();

        /** @var self $relationship */
        $relationship = $this->getRelationship();

        /** @var Builder $query */
        $query->select(
            $relationship->getTable().'.*',
            $query->getModel()->getTable().'.*',
        );

        return $query;
    }

    protected function handleRecordCreation(array $data) : Model
    {
        /** @var self $relationship */
        $relationship = $this->getRelationship();

        $pivotColumns = $relationship->getPivotColumns();
        $pivotData    = Arr::only($data, $pivotColumns);
        $data         = Arr::except($data, $pivotColumns);

        $record = $relationship->getQuery()->create($data);
        $this->getMountedTableActionForm()->model($record)->saveRelationships();
        $relationship->attach($record, $pivotData);

        return $record;
    }

    protected function handleRecordUpdate(Model $record, array $data) : Model
    {
        /** @var self $relationship */
        $relationship = $this->getRelationship();

        $pivotColumns = $relationship->getPivotColumns();
        $pivotData    = Arr::only($data, $pivotColumns);
        $data         = Arr::except($data, $pivotColumns);

        $record->update($data);

        if (\count($pivotColumns)) {
            $relationship->updateExistingPivot($record, $pivotData);
        }

        return $record;
    }
}
