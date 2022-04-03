<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions\Concerns;

use Dasher\Pages\Actions\ModalButtonAction;

trait CanOpenModal
{
    protected bool $isModalCentered     = null;
    protected ?array $modalActions      = null;
    protected ?string $modalButtonLabel = null;
    protected ?string $modalHeading     = null;
    protected ?string $modalSubheading  = null;
    protected ?string $modalWidth       = null;

    public function centerModal(bool $condition = true) : static
    {
        $this->isModalCentered = $condition;

        return $this;
    }

    public function getModalActions() : array
    {
        if ($this->modalActions !== null) {
            return $this->modalActions;
        }

        $color = $this->getColor();

        $actions = [
            ModalButtonAction::make('submit')
                ->label($this->getModalButtonLabel())
                ->submit('callMountedAction')
                ->color($color !== 'secondary' ? $color : null),
            ModalButtonAction::make('cancel')
                ->label(\__('admin::pages/actions.modal.buttons.cancel.label'))
                ->cancel()
                ->color('secondary'),
        ];

        if ($this->isModalCentered()) {
            $actions = \array_reverse($actions);
        }

        return $actions;
    }

    public function getModalButtonLabel() : string
    {
        if (\filled($this->modalButtonLabel)) {
            return $this->modalButtonLabel;
        }

        if ($this->isConfirmationRequired()) {
            return \__('admin::pages/actions.modal.buttons.confirm.label');
        }

        return \__('admin::pages/actions.modal.buttons.submit.label');
    }

    public function getModalHeading() : string
    {
        return $this->modalHeading ?? $this->getLabel();
    }

    public function getModalSubheading() : ?string
    {
        if (\filled($this->modalSubheading)) {
            return $this->modalSubheading;
        }

        if ($this->isConfirmationRequired()) {
            return \__('admin::pages/actions.modal.requires_confirmation_subheading');
        }

        return null;
    }

    public function getModalWidth() : string
    {
        if (\filled($this->modalWidth)) {
            return $this->modalWidth;
        }

        if ($this->isConfirmationRequired()) {
            return 'sm';
        }

        return '4xl';
    }

    public function isModalCentered() : bool
    {
        if ($this->isModalCentered !== null) {
            return $this->isModalCentered;
        }

        if (\in_array($this->getModalWidth(), ['xs', 'sm'], true)) {
            return true;
        }

        return $this->isConfirmationRequired();
    }

    public function modalActions(?array $actions = null) : static
    {
        $this->modalActions = $actions;

        return $this;
    }

    public function modalButton(?string $label = null) : static
    {
        $this->modalButtonLabel = $label;

        return $this;
    }

    public function modalHeading(?string $heading = null) : static
    {
        $this->modalHeading = $heading;

        return $this;
    }

    public function modalSubheading(?string $subheading = null) : static
    {
        $this->modalSubheading = $subheading;

        return $this;
    }

    public function modalWidth(?string $width = null) : static
    {
        $this->modalWidth = $width;

        return $this;
    }

    public function shouldOpenModal() : bool
    {
        return $this->isConfirmationRequired() || $this->hasFormSchema();
    }
}
