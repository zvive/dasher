<?php

namespace Dasher\Tests\Forms\Fixtures;

use Dasher\Forms\Concerns\InteractsWithForms;
use Dasher\Forms\Contracts\HasForms;
use Livewire\Component;

class Livewire extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public static function make(): static
    {
        return new static();
    }

    public function getData()
    {
        return $this->data;
    }
}
