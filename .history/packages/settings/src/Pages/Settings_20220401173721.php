<?php

declare(strict_types=1);

namespace Dasher\Pages;

use Illuminate\Support\Str;
use Dasher\Forms\ComponentContainer;
use Dasher\Pages\Actions\ButtonAction;

/**
 * @property ComponentContainer $form
 */
class Settings extends Page
{
    public $data;
    protected static string $settings;
    protected static string $view = 'filament-spatie-laravel-settings-plugin::pages.settings-page';

    protected function callHook(string $hook) : void
    {
        if ( ! \method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}();
    }

    protected function fillForm() : void
    {
        $this->callHook('beforeFill');

        $settings = \app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function getFormActions() : array
    {
        return [
            ButtonAction::make('save')
                ->label(\__('filament-spatie-laravel-settings-plugin::pages/settings-page.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    protected function getForms() : array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->columns(2)
                ->inlineLabel(\config('filament.layout.forms.have_inline_labels')),
        ];
    }

    protected function getRedirectUrl() : ?string
    {
        return null;
    }

    protected function getSavedNotificationMessage() : ?string
    {
        return \__('filament-spatie-laravel-settings-plugin::pages/settings-page.messages.saved');
    }

    public static function getSettings() : string
    {
        return static::$settings ?? (string) Str::of(\class_basename(static::class))
            ->beforeLast('Settings')
            ->prepend('App\\Settings\\')
            ->append('Settings');
    }

    public function mount() : void
    {
        $this->fillForm();
    }

    protected function mutateFormDataBeforeFill(array $data) : array
    {
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data) : array
    {
        return $data;
    }

    public function save() : void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeSave($data);

        $this->callHook('beforeSave');

        $settings = \app(static::getSettings());

        $settings->fill($data);
        $settings->save();

        $this->callHook('afterSave');

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl);
        }

        if (\filled($this->getSavedNotificationMessage())) {
            $this->notify('success', $this->getSavedNotificationMessage());
        }
    }
}
