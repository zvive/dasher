<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

use Illuminate\Support\Str;
use Dasher\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Dasher\Pages\Actions\ModalButtonAction;

trait CanCreateRecords
{
    use UsesResourceForm;
    protected static bool $canCreateAnother = true;

    protected static function canCreateAnother() : bool
    {
        return static::$canCreateAnother;
    }

    public function create(bool $another = false) : void
    {
        $form = $this->getMountedActionForm();

        $this->callHook('beforeValidate');
        $this->callHook('beforeCreateValidate');

        $data = $form->getState();

        $this->callHook('afterValidate');
        $this->callHook('afterCreateValidate');

        $data = $this->mutateFormDataBeforeCreate($data);

        $this->callHook('beforeCreate');

        $record = $this->handleRecordCreation($data);

        $form->model($record)->saveRelationships();

        $this->mountedTableActionRecord = $record->getKey();

        $this->callHook('afterCreate');

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $form->model($record::class);
            $this->mountedTableActionRecord = null;

            $form->fill();
        }

        if (\filled($this->getCreatedNotificationMessage())) {
            $this->notify('success', $this->getCreatedNotificationMessage());
        }
    }

    public function createAndCreateAnother() : void
    {
        $this->create(another: true);
    }

    public static function disableCreateAnother() : void
    {
        static::$canCreateAnother = false;
    }

    protected function fillCreateForm() : void
    {
        $this->callHook('beforeFill');
        $this->callHook('beforeCreateFill');

        $this->getMountedActionForm()->fill();

        $this->callHook('afterFill');
        $this->callHook('afterCreateFill');
    }

    protected function getCreateAction() : Action
    {
        return parent::getCreateAction()
            ->url(null)
            ->form($this->getCreateFormSchema())
            ->mountUsing(fn () => $this->fillCreateForm())
            ->modalActions($this->getCreateActionModalActions())
            ->modalHeading(\__('dasher::resources/pages/list-records.actions.create.modal.heading', ['label' => Str::title(static::getResource()::getLabel())]))
            ->action(fn () => $this->create());
    }

    protected function getCreateActionCancelModalAction() : ModalButtonAction
    {
        return ModalButtonAction::make('cancel')
            ->label(\__('tables::table.actions.modal.buttons.cancel.label'))
            ->cancel()
            ->color('secondary');
    }

    protected function getCreateActionCreateAndCreateAnotherModalAction() : ModalButtonAction
    {
        return ModalButtonAction::make('createAndCreateAnother')
            ->label(\__('dasher::resources/pages/list-records.actions.create.modal.actions.create_and_create_another.label'))
            ->action('createAndCreateAnother')
            ->color('secondary');
    }

    protected function getCreateActionCreateModalAction() : ModalButtonAction
    {
        return ModalButtonAction::make('create')
            ->label(\__('dasher::resources/pages/list-records.actions.create.modal.actions.create.label'))
            ->submit('callMountedAction')
            ->color('primary');
    }

    protected function getCreateActionModalActions() : array
    {
        return \array_merge(
            [$this->getCreateActionCreateModalAction()],
            static::canCreateAnother() ? [$this->getCreateActionCreateAndCreateAnotherModalAction()] : [],
            [$this->getCreateActionCancelModalAction()],
        );
    }

    protected function getCreatedNotificationMessage() : ?string
    {
        return \__('dasher::resources/pages/list-records.actions.create.messages.created');
    }

    protected function getCreateFormSchema() : array
    {
        return $this->getResourceForm(columns: 2)->getSchema();
    }

    protected function handleRecordCreation(array $data) : Model
    {
        return static::getModel()::create($data);
    }

    protected function hasCreateAction() : bool
    {
        return static::getResource()::canCreate();
    }

    protected function mutateFormDataBeforeCreate(array $data) : array
    {
        return $data;
    }
}
