<?php

use Dasher\Http\Responses\Auth\Contracts\LogoutResponse;
use Dasher\Tests\Models\User;
use Dasher\Tests\TestCase;
use Illuminate\Http\RedirectResponse;

uses(TestCase::class);

it('can log a user out', function () {
    $this
        ->actingAs(User::factory()->create())
        ->post(route('dasher.auth.logout'))
        ->assertRedirect(route('dasher.auth.login'));

    $this->assertGuest();
});

it('allows a user to override the logout response', function () {
    $logoutResponseFake = new class () implements LogoutResponse {
        public function toResponse($request): RedirectResponse
        {
            return redirect()->to('https://example.com');
        }
    };

    $this->app->instance(LogoutResponse::class, $logoutResponseFake);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dasher.auth.logout'))
        ->assertRedirect('https://example.com');
});
