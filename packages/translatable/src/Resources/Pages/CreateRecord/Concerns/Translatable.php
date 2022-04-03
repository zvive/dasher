<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\CreateRecord\Concerns;

use Illuminate\Database\Eloquent\Model;
use Dasher\Resources\Pages\Concerns\HasActiveFormLocaleSelect;

trait Translatable
{
    use HasActions;
    use HasActiveFormLocaleSelect;

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
