<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Shows the login screen', function () {
    $response = $this->get(route('login'));
    $response->assertOk();
    $response->assertStatus(200);
    $response->assertSee('Iniciar Sesión');
});

test('Logs in an a verified user, successfully', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('Password123!'),
        'email_verified_at' => now(),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

test('Prevents login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'PasswordIncorrect',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Credenciales Incorrectas');

    $this->assertGuest();
});

test('Prevents unverified user from accessing dashboard', function () {
    User::factory()->unverified()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $dashboardResponse = $this->get(route('dashboard'));
    $dashboardResponse->assertRedirect(route('verification.notice'));
});

test('Does not allow access to dashboard if email is not verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('verification.notice'));
});

test('Allows access to dashboard if email is verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
});

test('Fails login if user does not exist', function () {
    $response = $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'noexiste@dominio.com',
            'password' => 'Password123',
        ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors([
        'email' => 'No se encontró ningún usuario con este correo electrónico.',
    ]);

    $this->assertGuest();
});
