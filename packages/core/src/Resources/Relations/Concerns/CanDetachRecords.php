<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations\Concerns;

use Dasher\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CanDetachRecords
{
    public function bulkDetach() : void
    {
        $this->callHook('beforeBulkDetach');

        /** @var BelongsToMany $relationship */
        $relationship = $this->getRelationship();

        $relationship->detach($this->getSelectedTableRecords());

        $this->callHook('afterBulkDetach');

        if (\filled($this->getBulkDetachedNotificationMessage())) {
            $this->notify('success', $this->getBulkDetachedNotificationMessage());
        }
    }

    protected function canDetach(Model $record) : bool
    {
        return $this->can('detach', $record);
    }

    protected function canDetachAny() : bool
    {
        return $this->can('detachAny');
    }

    public function detach() : void
    {
        $this->callHook('beforeDetach');

        /** @var BelongsToMany $relationship */
        $relationship = $this->getRelationship();

        $relationship->detach($this->getMountedTableActionRecord());

        $this->callHook('afterDetach');

        if (\filled($this->getDetachedNotificationMessage())) {
            $this->notify('success', $this->getDetachedNotificationMessage());
        }
    }

    protected function getBulkDetachedNotificationMessage() : ?string
    {
        return \__('dasher::resources/relation-managers/detach.bulk_action.messages.detached');
    }

    protected function getDetachAction() : Tables\Actions\Action
    {
        return \config('dasher.layout.tables.actions.type')::make('detach')
            ->label(\__('dasher::resources/relation-managers/detach.action.label'))
            ->requiresConfirmation()
            ->modalHeading(\__('dasher::resources/relation-managers/detach.action.modal.heading', ['label' => static::getRecordLabel()]))
            ->action(fn () => $this->detach())
            ->color('danger')
            ->icon('heroicon-o-x')
            ->hidden(fn (Model $record) : bool => ! static::canDetach($record));
    }

    protected function getDetachBulkAction() : Tables\Actions\BulkAction
    {
        return Tables\Actions\BulkAction::make('detach')
            ->label(\__('dasher::resources/relation-managers/detach.bulk_action.label'))
            ->action(fn () => $this->bulkDetach())
            ->requiresConfirmation()
            ->modalHeading(\__('dasher::resources/relation-managers/detach.bulk_action.modal.heading', ['label' => static::getPluralRecordLabel()]))
            ->deselectRecordsAfterCompletion()
            ->color('danger')
            ->icon('heroicon-o-x');
    }

    protected function getDetachedNotificationMessage() : ?string
    {
        return \__('dasher::resources/relation-managers/detach.action.messages.detached');
    }
}
