<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

use Illuminate\Database\Eloquent\Model;
use Dasher\Tables\Actions\Action as TableAction;

trait CanDeleteRecords
{
    public function delete() : void
    {
        $this->callHook('beforeDelete');

        $this->handleRecordDeletion($this->getMountedTableActionRecord());

        $this->callHook('afterDelete');

        if (\filled($this->getDeletedNotificationMessage())) {
            $this->notify('success', $this->getDeletedNotificationMessage());
        }
    }

    protected function getDeleteAction() : TableAction
    {
        return \config('dasher.layout.tables.actions.type')::make('delete')
            ->label(\__('dasher::resources/pages/list-records.table.actions.delete.label'))
            ->action(fn () => $this->delete())
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->hidden(fn (Model $record) : bool => ! static::getResource()::canDelete($record));
    }

    protected function getDeletedNotificationMessage() : ?string
    {
        return \__('dasher::resources/pages/list-records.table.actions.delete.messages.deleted');
    }

    protected function handleRecordDeletion(Model $record) : void
    {
        $record->delete();
    }

    protected function hasDeleteAction() : bool
    {
        return true;
    }
}
