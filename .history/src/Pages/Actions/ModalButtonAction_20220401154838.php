<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions;

class ModalButtonAction extends Action
{
    use Concerns\CanBeOutlined;
    use Concerns\HasIcon;
    protected ?string $iconPosition = null;
    protected string $view          = 'filament::pages.actions.modal.actions.button-action';

    public function getIconPosition() : ?string
    {
        return $this->iconPosition;
    }

    public function iconPosition(string $position) : static
    {
        $this->iconPosition = $position;

        return $this;
    }
}
