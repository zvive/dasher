<?php

declare(strict_types=1);

namespace Dasher\Resources\Relationships\Concerns;

use Dasher\Tables;
use Illuminate\Database\Eloquent\Model;

trait CanEditRecords
{
    protected function canEdit(Model $record) : bool
    {
        return $this->can('update', $record);
    }

    protected function fillEditForm() : void
    {
        $this->callHook('beforeFill');
        $this->callHook('beforeEditFill');

        $data = $this->getMountedTableActionRecord()->toArray();

        $data = $this->mutateFormDataBeforeFill($data);

        $this->getMountedTableActionForm()->fill($data);

        $this->callHook('afterFill');
        $this->callHook('afterEditFill');
    }

    protected function getEditAction() : Tables\Actions\Action
    {
        return \config('dasher.layout.tables.actions.type')::make('edit')
            ->label(\__('dasher::resources/relation-managers/edit.action.label'))
            ->form($this->getEditFormSchema())
            ->mountUsing(fn () => $this->fillEditForm())
            ->modalButton(\__('dasher::resources/relation-managers/edit.action.modal.actions.save.label'))
            ->modalHeading(\__('dasher::resources/relation-managers/edit.action.modal.heading', ['label' => static::getRecordLabel()]))
            ->action(fn () => $this->save())
            ->icon('heroicon-o-pencil')
            ->hidden(fn (Model $record) : bool => ! static::canEdit($record));
    }

    protected function getEditFormSchema() : array
    {
        return $this->getResourceForm(columns: 2)->getSchema();
    }

    protected function getSavedNotificationMessage() : ?string
    {
        return \__('dasher::resources/relation-managers/edit.action.messages.saved');
    }

    protected function handleRecordUpdate(Model $record, array $data) : Model
    {
        $record->update($data);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data) : array
    {
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data) : array
    {
        return $data;
    }

    public function save() : void
    {
        $this->callHook('beforeValidate');
        $this->callHook('beforeEditValidate');

        $data = $this->getMountedTableActionForm()->getState();

        $this->callHook('afterValidate');
        $this->callHook('afterEditValidate');

        $data = $this->mutateFormDataBeforeSave($data);

        $this->callHook('beforeSave');

        $this->handleRecordUpdate($this->getMountedTableActionRecord(), $data);

        $this->callHook('afterSave');

        if (\filled($this->getSavedNotificationMessage())) {
            $this->notify('success', $this->getSavedNotificationMessage());
        }
    }
}
