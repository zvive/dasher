<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

use Dasher\Tables;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait CanEditRecords
{
    use UsesResourceForm;

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
        $resource = static::getResource();

        return parent::getEditAction()
            ->url(null)
            ->form($this->getEditFormSchema())
            ->mountUsing(fn () => $this->fillEditForm())
            ->modalButton(\__('dasher::resources/pages/list-records.table.actions.edit.modal.actions.save.label'))
            ->modalHeading(fn (Model $record) => \__('dasher::resources/pages/list-records.table.actions.edit.modal.heading', ['label' => $resource::hasRecordTitle() ? $resource::getRecordTitle($record) : Str::title($resource::getLabel())]))
            ->action(fn ()                    => $this->save())
            ->hidden(fn (Model $record)       => ! $resource::canEdit($record));
    }

    protected function getEditFormSchema() : array
    {
        return $this->getResourceForm(columns: 2)->getSchema();
    }

    protected function getSavedNotificationMessage() : ?string
    {
        return \__('dasher::resources/pages/list-records.table.actions.edit.messages.saved');
    }

    protected function handleRecordUpdate(Model $record, array $data) : Model
    {
        $record->update($data);

        return $record;
    }

    protected function hasEditAction() : bool
    {
        return true;
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
