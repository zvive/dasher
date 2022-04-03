<?php

declare(strict_types=1);

namespace Dasher\Pages;

use Closure;
use Illuminate\Support\Facades\Route;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort    = -2;
    protected static string $view            = 'dasher::pages.dashboard';

    protected static function getNavigationLabel() : string
    {
        return static::$navigationLabel ?? static::$title ?? \__('dasher::pages/dashboard.title');
    }

    public static function getRoutes() : Closure
    {
        return function () {
            Route::get('/', static::class)->name(static::getSlug());
        };
    }

    protected function getTitle() : string
    {
        return static::$title ?? \__('dasher::pages/dashboard.title');
    }
}
