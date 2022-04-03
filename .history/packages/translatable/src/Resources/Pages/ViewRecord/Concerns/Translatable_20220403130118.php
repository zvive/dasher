<?php

declare(strict_types=1);

namespace Filament\Resources\Pages\ViewRecord\Concerns;

use Filament\Resources\Pages\Concerns\HasActiveFormLocaleSelect;

trait Translatable
{
    use HasActions;
    use HasActiveFormLocaleSelect;

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
}
