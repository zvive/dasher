<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations\Concerns;

use Dasher\Tables;
use Illuminate\Database\Eloquent\Model;

trait CanDeleteRecords
{
    public function bulkDelete() : void
    {
        $this->callHook('beforeBulkDelete');

        $this->getSelectedTableRecords()->each(fn (Model $record) => $record->delete());

        $this->callHook('afterBulkDelete');

        if (\filled($this->getBulkDeletedNotificationMessage())) {
            $this->notify('success', $this->getBulkDeletedNotificationMessage());
        }
    }

    protected function canDelete(Model $record) : bool
    {
        return $this->can('delete', $record);
    }

    protected function canDeleteAny() : bool
    {
        return $this->can('deleteAny');
    }

    public function delete() : void
    {
        $this->callHook('beforeDelete');

        $this->getMountedTableActionRecord()->delete();

        $this->callHook('afterDelete');

        if (\filled($this->getDeletedNotificationMessage())) {
            $this->notify('success', $this->getDeletedNotificationMessage());
        }
    }

    protected function getBulkDeletedNotificationMessage() : ?string
    {
        return \__('dasher::resources/relation-managers/delete.bulk_action.messages.deleted');
    }

    protected function getDeleteAction() : Tables\Actions\Action
    {
        return \config('dasher.layout.tables.actions.type')::make('delete')
            ->label(\__('dasher::resources/relation-managers/delete.action.label'))
            ->requiresConfirmation()
            ->modalHeading(\__('dasher::resources/relation-managers/delete.action.modal.heading', ['label' => static::getRecordLabel()]))
            ->action(fn () => $this->delete())
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->hidden(fn (Model $record) : bool => ! static::canDelete($record));
    }

    protected function getDeleteBulkAction() : Tables\Actions\BulkAction
    {
        return Tables\Actions\BulkAction::make('delete')
            ->label(\__('dasher::resources/relation-managers/delete.bulk_action.label'))
            ->action(fn () => $this->bulkDelete())
            ->requiresConfirmation()
            ->modalHeading(\__('dasher::resources/relation-managers/delete.bulk_action.modal.heading', ['label' => static::getPluralRecordLabel()]))
            ->deselectRecordsAfterCompletion()
            ->color('danger')
            ->icon('heroicon-o-trash');
    }

    protected function getDeletedNotificationMessage() : ?string
    {
        return \__('dasher::resources/relation-managers/delete.action.messages.deleted');
    }
}
