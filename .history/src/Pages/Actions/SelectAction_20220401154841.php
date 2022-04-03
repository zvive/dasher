<?php

declare(strict_types=1);

namespace Dasher\Pages\Actions;

use Illuminate\Contracts\Support\Arrayable;

class SelectAction extends Action
{
    use Concerns\HasId;
    protected array | Arrayable $options = [];
    protected ?string $placeholder       = null;
    protected string $view               = 'admin::pages.actions.select-action';

    public function getOptions() : array
    {
        $options = $this->options;

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }

    public function getPlaceholder() : ?string
    {
        return $this->placeholder;
    }

    public function options(array | Arrayable $options) : static
    {
        $this->options = $options;

        return $this;
    }

    public function placeholder(string $placeholder) : static
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
