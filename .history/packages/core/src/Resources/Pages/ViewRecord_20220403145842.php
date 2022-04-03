<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages;

use Illuminate\Support\Str;
use Dasher\Pages\Actions\Action;
use Dasher\Forms\ComponentContainer;
use Dasher\Pages\Actions\ButtonAction;
use Illuminate\Database\Eloquent\Model;

/**
 * @property ComponentContainer $form
 */
class ViewRecord extends Page
{
    use Concerns\HasRecordBreadcrumb;
    use Concerns\HasRelations;
    use Concerns\InteractsWithRecord;
    use Concerns\UsesResourceForm;
    public $data;
    public $record;
    protected $queryString = [
        'activeRelationManager',
    ];
    protected static string $view = 'dasher::resources.pages.view-record';

    protected function fillForm() : void
    {
        $this->callHook('beforeFill');

        $this->form->fill($this->record->toArray());

        $this->callHook('afterFill');
    }

    protected function getActions() : array
    {
        $resource = static::getResource();

        if ( ! $resource::hasPage('edit')) {
            return [];
        }

        if ( ! $resource::canEdit($this->record)) {
            return [];
        }

        return [$this->getEditAction()];
    }

    public function getBreadcrumb() : string
    {
        return static::$breadcrumb ?? \__('dasher::resources/pages/view-record.breadcrumb');
    }

    protected function getEditAction() : Action
    {
        return ButtonAction::make('edit')
            ->label(\__('dasher::resources/pages/view-record.actions.edit.label'))
            ->url(fn () => static::getResource()::getUrl('edit', ['record' => $this->record]));
    }

    protected function getForms() : array
    {
        return [
            'form' => $this->makeForm()
                ->disabled()
                ->model($this->record)
                ->schema($this->getResourceForm(columns: \config('dasher.layout.forms.have_inline_labels') ? 1 : 2)->getSchema())
                ->statePath('data')
                ->inlineLabel(\config('dasher.layout.forms.have_inline_labels')),
        ];
    }

    protected function getMountedActionFormModel() : Model
    {
        return $this->record;
    }

    protected function getTitle() : string
    {
        if (\filled(static::$title)) {
            return static::$title;
        }

        if (\filled($recordTitle = $this->getRecordTitle())) {
            return $recordTitle;
        }

        return Str::title(static::getResource()::getLabel());
    }

    public function mount($record) : void
    {
        static::authorizeResourceAccess();

        $this->record = $this->getRecord($record);

        \abort_unless(static::getResource()::canView($this->record), 403);

        $this->fillForm();
    }
}
