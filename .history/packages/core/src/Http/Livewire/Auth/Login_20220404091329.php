<?php

declare(strict_types=1);

namespace Dasher\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Dasher\Forms\ComponentContainer;
use Dasher\Forms\Contracts\HasForms;
use Dasher\Forms\Components\Checkbox;
use Dasher\Forms\Components\TextInput;
use Dasher\Forms\Concerns\InteractsWithForms;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Dasher\Http\Responses\Auth\Contracts\LoginResponse;
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
            $this->addError('email', \__('dasher::login.messages.throttled', [
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
            $this->addError('email', \__('dasher::login.messages.failed'));

            return null;
        }

        return \app(LoginResponse::class);
    }

    protected function getFormSchema() : array
    {
        return [
            TextInput::make('email')
                ->label(\__('dasher::login.fields.email.label'))
                ->email()
                ->required()
                ->autocomplete(),
            TextInput::make('password')
                ->label(\__('dasher::login.fields.password.label'))
                ->password()
                ->required(),
            Checkbox::make('remember')
                ->label(\__('dasher::login.fields.remember.label')),
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
        return \view('dasher::login')
            ->layout('dasher::components.layouts.base', [
                'title' => \__('dasher::login.title'),
            ]);
    }
}
