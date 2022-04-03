<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages;

use Illuminate\Support\Str;
use Dasher\Pages\Actions\Action;
use Dasher\Pages\Actions\ButtonAction;
use Filament\Forms\ComponentContainer;
use Illuminate\Database\Eloquent\Model;
use Dasher\Pages\Contracts\HasFormActions;

/**
 * @property ComponentContainer $form
 */
class CreateRecord extends Page implements HasFormActions
{
    use Concerns\UsesResourceForm;
    public $data;
    public $record;
    protected static bool $canCreateAnother = true;
    protected static string $view           = 'filament::resources.pages.create-record';

    protected static function canCreateAnother() : bool
    {
        return static::$canCreateAnother;
    }

    public function create(bool $another = false) : void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeCreate($data);

        $this->callHook('beforeCreate');

        $this->record = $this->handleRecordCreation($data);

        $this->form->model($this->record)->saveRelationships();

        $this->callHook('afterCreate');

        if (\filled($this->getCreatedNotificationMessage())) {
            $this->notify(
                'success',
                $this->getCreatedNotificationMessage(),
                isAfterRedirect: ! $another,
            );
        }

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $this->redirect($this->getRedirectUrl());
    }

    public function createAndCreateAnother() : void
    {
        $this->create(another: true);
    }

    public static function disableCreateAnother() : void
    {
        static::$canCreateAnother = false;
    }

    protected function fillForm() : void
    {
        $this->callHook('beforeFill');

        $this->form->fill();

        $this->callHook('afterFill');
    }

    public function getBreadcrumb() : string
    {
        return static::$breadcrumb ?? \__('filament::resources/pages/create-record.breadcrumb');
    }

    protected function getCancelFormAction() : Action
    {
        return ButtonAction::make('cancel')
            ->label(\__('filament::resources/pages/create-record.form.actions.cancel.label'))
            ->url(static::getResource()::getUrl())
            ->color('secondary');
    }

    protected function getCreateAndCreateAnotherFormAction() : Action
    {
        return ButtonAction::make('createAnother')
            ->label(\__('filament::resources/pages/create-record.form.actions.create_and_create_another.label'))
            ->action('createAndCreateAnother')
            ->color('secondary');
    }

    protected function getCreatedNotificationMessage() : ?string
    {
        return \__('filament::resources/pages/create-record.messages.created');
    }

    protected function getCreateFormAction() : Action
    {
        return ButtonAction::make('create')
            ->label(\__('filament::resources/pages/create-record.form.actions.create.label'))
            ->submit('create');
    }

    protected function getFormActions() : array
    {
        return \array_merge(
            [$this->getCreateFormAction()],
            static::canCreateAnother() ? [$this->getCreateAndCreateAnotherFormAction()] : [],
            [$this->getCancelFormAction()],
        );
    }

    protected function getForms() : array
    {
        return [
            'form' => $this->makeForm()
                ->model(static::getModel())
                ->schema($this->getResourceForm(columns: \config('filament.layout.forms.have_inline_labels') ? 1 : 2)->getSchema())
                ->statePath('data')
                ->inlineLabel(\config('filament.layout.forms.have_inline_labels')),
        ];
    }

    protected function getMountedActionFormModel() : string
    {
        return static::getModel();
    }

    protected function getRedirectUrl() : string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view') && $resource::canView($this->record)) {
            return $resource::getUrl('view', ['record' => $this->record]);
        }

        if ($resource::hasPage('edit') && $resource::canEdit($this->record)) {
            return $resource::getUrl('edit', ['record' => $this->record]);
        }

        return $resource::getUrl('index');
    }

    protected function getTitle() : string
    {
        if (\filled(static::$title)) {
            return static::$title;
        }

        return \__('filament::resources/pages/create-record.title', [
            'label' => Str::title(static::getResource()::getLabel()),
        ]);
    }

    protected function handleRecordCreation(array $data) : Model
    {
        return static::getModel()::create($data);
    }

    public function mount() : void
    {
        static::authorizeResourceAccess();

        \abort_unless(static::getResource()::canCreate(), 403);

        $this->fillForm();
    }

    protected function mutateFormDataBeforeCreate(array $data) : array
    {
        return $data;
    }
}
