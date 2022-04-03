<?php

declare(strict_types=1);

namespace Dasher\Forms\Components\Builder;

use Closure;
use Illuminate\Support\Str;
use Dasher\Forms\Components\Concerns;
use Dasher\Forms\Components\Component;

class Block extends Component
{
    use Concerns\HasName;
    protected string | Closure | null $icon = null;
    protected string $view                  = 'forms::components.builder.block';

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public function getIcon() : ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getLabel() : string
    {
        return parent::getLabel() ?? (string) Str::of($this->getName())
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();
    }

    public function icon(string | Closure | null $icon) : static
    {
        $this->icon = $icon;

        return $this;
    }

    public static function make(string $name) : static
    {
        return \app(static::class, ['name' => $name]);
    }
}
