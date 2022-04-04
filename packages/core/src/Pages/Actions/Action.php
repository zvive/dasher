<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Traits\Tappable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Conditionable;

class Action extends Component implements Htmlable
{
    use Concerns\BelongsToLivewire;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanBeMounted;
    use Concerns\CanOpenUrl;
    use Concerns\CanOpenModal;
    use Concerns\CanRequireConfirmation;
    use Concerns\HasAction;
    use Concerns\HasColor;
    use Concerns\HasFormSchema;
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

    public function call(array $data = [])
    {
        if ($this->isHidden() || $this->isDisabled()) {
            return;
        }

        $action = $this->getAction();

        if (\is_string($action)) {
            $action = Closure::fromCallable([$this->getLivewire(), $action]);
        }

        return \app()->call($action, [
            'data' => $data,
        ]);
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
