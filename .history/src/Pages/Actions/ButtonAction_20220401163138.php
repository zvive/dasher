<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions;

class ButtonAction extends Action
{
    use Concerns\CanBeOutlined;
    use Concerns\CanSubmitForm;
    use Concerns\HasIcon;
    use Concerns\HasTooltip;
    protected ?string $iconPosition = null;
    protected string $view          = 'dasher::pages.actions.button-action';

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
