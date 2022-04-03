<?php

declare(strict_types=1);

namespace Admin\Pages\Actions;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Traits\Tappable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Conditionable;

class ModalAction extends Component implements Htmlable
{
    use Concerns\CanCancelAction;
    use Concerns\CanSubmitForm;
    use Concerns\HasAction;
    use Concerns\HasColor;
    use Concerns\HasLabel;
    use Concerns\HasName;
    use Concerns\HasView;
    use Conditionable;
    use Macroable;
    use Tappable;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    protected function setUp() : void
    {
    }

    public static function make(string $name) : static
    {
        $static = \app(static::class, ['name' => $name]);
        $static->setUp();

        return $static;
    }

    public function render() : View
    {
        return \view($this->getView(), \array_merge($this->data(), [
            'action' => $this,
        ]));
    }

    public function toHtml() : string
    {
        return $this->render()->render();
    }
}
