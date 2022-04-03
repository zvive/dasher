<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

use Dasher\Resources\Form;
use Dasher\Pages\Concerns\HasFormActions;

trait UsesResourceForm
{
    use HasFormActions;
    protected ?Form $resourceForm = null;

    protected function form(Form $form) : Form
    {
        return static::getResource()::form($form);
    }

    protected function getResourceForm(?int $columns = null) : Form
    {
        if ( ! $this->resourceForm) {
            $this->resourceForm = $this->form(
                Form::make()->columns($columns),
            );
        }

        return $this->resourceForm;
    }
}
