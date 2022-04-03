<?php

declare(strict_types=1);

namespace Filament\Resources\Pages\CreateRecord\Concerns;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\Concerns\HasActiveFormLocaleSelect;

trait Translatable
{
    use HasActiveFormLocaleSelect;

    protected function getActions() : array
    {
        return \array_merge(
            [$this->getActiveFormLocaleSelectAction()],
            parent::getActions() ?? [],
        );
    }

    protected function handleRecordCreation(array $data) : Model
    {
        $record = static::getModel()::usingLocale(
            $this->activeFormLocale,
        )->fill($data);
        $record->save();

        return $record;
    }

    public function mount() : void
    {
        static::authorizeResourceAccess();

        \abort_unless(static::getResource()::canCreate(), 403);

        $this->setActiveFormLocale();

        $this->fillForm();
    }

    protected function setActiveFormLocale() : void
    {
        $this->activeFormLocale = static::getResource()::getDefaultTranslatableLocale();
    }
}
