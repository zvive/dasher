<?php

declare(strict_types=1);

namespace Dasher\Commands;

use Dasher\Facades\Dasher;
use Illuminate\Console\Command;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\EloquentUserProvider;

class MakeUserCommand extends Command
{
    use Concerns\CanValidateInput;
    protected $description = 'Creates a Dasher user.';
    protected $signature   = 'z:dash-user';

    public function handle() : int
    {
        /** @var SessionGuard $auth */
        $auth = Dasher::auth();

        /** @var EloquentUserProvider $userProvider */
        $userProvider = $auth->getProvider();

        $userModel = $userProvider->getModel();

        $user = $userModel::create([
            'name'     => $this->validateInput(fn ()     => $this->ask('Name'), 'name', ['required']),
            'email'    => $this->validateInput(fn ()    => $this->ask('Email address'), 'email', ['required', 'email', 'unique:'.$userModel]),
            'password' => Hash::make($this->validateInput(fn () => $this->secret('Password'), 'password', ['required', 'min:8'])),
        ]);

        $loginUrl = \route('dasher.auth.login');
        $this->info("Success! {$user->email} may now log in at {$loginUrl}.");

        if ($userProvider->getModel()::count() === 1 && $this->confirm('Would you like to show some love by starring the repo?', true)) {
            if (PHP_OS_FAMILY === 'Darwin') {
                \exec('open https://github.com/zvive/dasher');
            }
            if (PHP_OS_FAMILY === 'Linux') {
                \exec('xdg-open https://github.com/zvive/dasher');
            }
            if (PHP_OS_FAMILY === 'Windows') {
                \exec('start https://github.com/zvive/dasher');
            }

            $this->line('Thank you!');
        }

        return static::SUCCESS;
    }
}
