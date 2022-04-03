<?php

declare(strict_types=1);

namespace Admin\Pages\Concerns;

use Admin\Forms;
use Admin\Pages\Contracts;
use Admin\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Forms\ComponentContainer $mountedActionForm
 */
trait HasActions
{
    use Forms\Concerns\InteractsWithForms;
    public $mountedAction           = null;
    public $mountedActionData       = [];
    protected ?array $cachedActions = null;

    protected function cacheActions() : void
    {
        $this->cachedActions = \collect($this->getActions())
            ->mapWithKeys(function (Action $action) : array {
                $action->livewire($this);

                return [$action->getName() => $action];
            })
            ->toArray();
    }

    public function callMountedAction()
    {
        $action = $this->getMountedAction();

        if ( ! $action) {
            return;
        }

        if ($action->isHidden()) {
            return;
        }

        $data = $this->getMountedActionForm()->getState();

        try {
            return $action->call($data);
        } finally {
            $this->dispatchBrowserEvent('close-modal', [
                'id' => 'page-action',
            ]);
        }
    }

    protected function getActions() : array
    {
        return [];
    }

    protected function getCachedAction(string $name) : ?Action
    {
        return $this->getCachedActions()[$name] ?? null;
    }

    protected function getCachedActions() : array
    {
        if ($this->cachedActions === null) {
            $this->cacheActions();
        }

        return $this->cachedActions;
    }

    protected function getHasActionsForms() : array
    {
        return [
            'mountedActionForm' => $this->makeForm()
                ->schema(($action = $this->getMountedAction()) ? $action->getFormSchema() : [])
                ->statePath('mountedActionData')
                ->model($this->getMountedActionFormModel()),
        ];
    }

    public function getMountedAction() : ?Action
    {
        if ( ! $this->mountedAction) {
            return null;
        }

        $action = $this->getCachedAction($this->mountedAction);

        if ($action) {
            return $action;
        }

        if ( ! $this instanceof Contracts\HasFormActions) {
            return null;
        }

        return $this->getCachedFormAction($this->mountedAction);
    }

    public function getMountedActionForm() : Forms\ComponentContainer
    {
        return $this->mountedActionForm;
    }

    protected function getMountedActionFormModel() : Model | string | null
    {
        return null;
    }

    public function mountAction(string $name)
    {
        $this->mountedAction = $name;

        $action = $this->getMountedAction();

        if ( ! $action) {
            return;
        }

        if ($action->isHidden()) {
            return;
        }

        $this->cacheForm('mountedActionForm');

        \app()->call($action->getMountUsing(), [
            'action' => $action,
            'form'   => $this->getMountedActionForm(),
        ]);

        if ( ! $action->shouldOpenModal()) {
            return $this->callMountedAction();
        }

        $this->resetErrorBag();

        $this->dispatchBrowserEvent('open-modal', [
            'id' => 'page-action',
        ]);
    }
}
