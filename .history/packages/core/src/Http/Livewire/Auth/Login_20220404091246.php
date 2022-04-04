<?php

declare(strict_types=1);

namespace Filament\Http\Livewire\Auth;

use Livewire\Component;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

/**
 * @property ComponentContainer $form
 */
class Login extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;
    public $email    = '';
    public $password = '';
    public $remember = false;

    public function authenticate() : ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->addError('email', \__('filament::login.messages.throttled', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => \ceil($exception->secondsUntilAvailable / 60),
            ]));

            return null;
        }

        $data = $this->form->getState();

        if ( ! Filament::auth()->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $data['remember'])) {
            $this->addError('email', \__('filament::login.messages.failed'));

            return null;
        }

        return \app(LoginResponse::class);
    }

    protected function getFormSchema() : array
    {
        return [
            TextInput::make('email')
                ->label(\__('filament::login.fields.email.label'))
                ->email()
                ->required()
                ->autocomplete(),
            TextInput::make('password')
                ->label(\__('filament::login.fields.password.label'))
                ->password()
                ->required(),
            Checkbox::make('remember')
                ->label(\__('filament::login.fields.remember.label')),
        ];
    }

    public function mount() : void
    {
        if (Filament::auth()->check()) {
            \redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function render() : View
    {
        return \view('filament::login')
            ->layout('filament::components.layouts.base', [
                'title' => \__('filament::login.title'),
            ]);
    }
}
