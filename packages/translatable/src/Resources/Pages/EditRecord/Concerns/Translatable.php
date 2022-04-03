<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\EditRecord\Concerns;

use Illuminate\Database\Eloquent\Model;
use Dasher\Resources\Pages\Concerns\FillsForms;
use Dasher\Resources\Pages\Concerns\HasActions;
use Dasher\Resources\Pages\Concerns\HasActiveFormLocaleSelect;

trait Translatable
{
    use FillsForms;
    use HasActions;
    use HasActiveFormLocaleSelect;
    public $activeFormLocale = null;

    protected function handleRecordUpdate(Model $record, array $data) : Model
    {
        $record->setLocale($this->activeFormLocale)->fill($data)->save();

        return $record;
    }

    protected function setActiveFormLocale() : void
    {
        $resource = static::getResource();

        $availableLocales = \array_keys($this->record->getTranslations($resource::getTranslatableAttributes()[0]));
        $resourceLocales  = $this->getTranslatableLocales();
        $defaultLocale    = $resource::getDefaultTranslatableLocale();

        $this->activeFormLocale = \in_array($defaultLocale, $availableLocales, true) ? $defaultLocale : \array_intersect($availableLocales, $resourceLocales)[0] ?? $defaultLocale;
        $this->record->setLocale($this->activeFormLocale);
    }

    public function updatedActiveFormLocale() : void
    {
        $this->fillForm();
    }

    public function updatingActiveFormLocale() : void
    {
        $this->save(shouldRedirect: false);
    }
}
