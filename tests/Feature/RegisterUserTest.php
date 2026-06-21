<?php

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('Shows the registration screen', function () {
    $response = $this->get(route('register'));
    $response->assertOk();
    $response->assertStatus(200);
    $response->assertSee('Crear Cuenta');
    $response->assertSee('Registrarme');
    $response->assertSeeInOrder(['Crear Cuenta', 'Registrarme']);
});

test('Registers a new user as unverified and dispatches the registered event', function () {
    Event::fake();
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('verification.notice'));
    $user = User::query()->where('email', 'test@example.com')->first();

    expect($user)->not()->toBeNull();
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);

});

test('Should validate required fields when the request body is empty', function () {
    $response = $this->post(route('register.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password',
    ]);

    $response->assertSessionHasErrors([
        'name' => 'El campo nombre es obligatorio',
        'email' => 'El campo email es obligatorio',
        'password' => 'El campo contraseña es obligatorio',
    ]);

});

test('Prevents duplicated emails', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors([
        'email' => 'El email ya está registrado',
    ]);
});

test('It should sends a verification email after registration', function () {
    Notification::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $user = User::query()->where('email', 'test@example.com')->first();
    Notification::assertSentTo($user, VerifyEmail::class);
});

test('Verifies the email verification process', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $response = $this->actingAs($user)->get($verificationUrl);
    $response->assertRedirect(route('dashboard'));

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('Prevents unverified users from accessing the dashboard', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('verification.notice'));
});

it('Allows verified users to access the dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
});
