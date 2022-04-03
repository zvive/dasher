<?php

declare(strict_types=1);

namespace Dasher\Profile\Pages;

use Dasher\Pages\Page;
use Dasher\Forms\Components\Grid;
use Dasher\Forms\Components\Section;
use Dasher\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Hash;
use Dasher\Forms\Components\TextInput;
use Dasher\Forms\Concerns\InteractsWithForms;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    public $current_password;
    public $email;
    public $name;
    public $new_password;
    public $new_password_confirmation;
    protected static ?string $navigationGroup = 'Account';
    protected static ?string $navigationIcon  = 'heroicon-o-user';
    protected static string $view             = 'filament-profile::filament.pages.profile';

    protected function getBreadcrumbs() : array
    {
        return [
            \url()->current() => 'Profile',
        ];
    }

    public function getCancelButtonUrlProperty()
    {
        return static::getUrl();
    }

    protected function getFormSchema() : array
    {
        return [
            Section::make('General')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->required(),
                ]),
            Section::make('Update Password')
                ->columns(2)
                ->schema([
                    TextInput::make('current_password')
                        ->label('Current Password')
                        ->password()
                        ->rules(['required_with:new_password'])
                        ->currentPassword()
                        ->autocomplete('off')
                        ->columnSpan(1),
                    Grid::make()
                        ->schema([
                            TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->rules(['confirmed'])
                                ->autocomplete('new-password'),
                            TextInput::make('new_password_confirmation')
                                ->label('Confirm Password')
                                ->password()
                                ->rules([
                                    'required_with:new_password',
                                ])
                                ->autocomplete('new-password'),
                        ]),
                ]),
        ];
    }

    public function mount()
    {
        $this->form->fill([
            'name'  => \auth()->user()->name,
            'email' => \auth()->user()->email,
        ]);
    }

    public function submit()
    {
        $this->form->getState();

        $state = \array_filter([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->new_password ? Hash::make($this->new_password) : null,
        ]);

        \auth()->user()->update($state);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->notify('success', 'Your profile has been updated.');
    }
}
