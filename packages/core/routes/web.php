<?php

declare(strict_types=1);

use Dasher\Facades\Dasher;
use Illuminate\Support\Facades\Route;
use Dasher\Http\Controllers\AssetController;
use Dasher\Http\Responses\Auth\Contracts\LogoutResponse;

Route::domain(config('dasher.domain'))
    ->middleware(config('dasher.middleware.base'))
    ->name('dasher.')
    ->group(function () {
        Route::prefix(config('dasher.core_path'))->group(function () {
            Route::get('/assets/{file}', AssetController::class)->where('file', '.*')->name('asset');

            Route::post('/logout', function () : LogoutResponse {
                Dasher::auth()->logout();

                session()->invalidate();
                session()->regenerateToken();

                return app(LogoutResponse::class);
            })->name('auth.logout');
        });

        Route::prefix(config('dasher.path'))->group(function () {
            if ($loginPage = config('dasher.auth.pages.login')) {
                Route::get('/login', $loginPage)->name('auth.login');
            }

            Route::middleware(config('dasher.middleware.auth'))->group(function () : void {
                Route::name('pages.')->group(function () : void {
                    foreach (Dasher::getPages() as $page) {
                        Route::group([], $page::getRoutes());
                    }
                });

                Route::name('resources.')->group(function () : void {
                    foreach (Dasher::getResources() as $resource) {
                        Route::group([], $resource::getRoutes());
                    }
                });
            });
        });
    });
