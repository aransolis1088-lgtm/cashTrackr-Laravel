<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Validates required fields when creating a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => '',
            'amount' => '',
            'type' => '',
        ]);

    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors([
        'name',
        'amount',
        'type',
    ]);

});

it('Does not allow guest to create a budget', function () {
    $response = $this->post(route('budgets.store'), [
        'name' => 'My Budget',
        'amount' => 1000,
        'type' => 'general',
    ]);

    $response->assertRedirect(route('login'));
});

it('Assigns the created budget to the authenticated user', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)
        ->post(route('budgets.store'), [
            'name' => 'My Budget',
            'amount' => 1000,
            'type' => 'general',
        ]);

    $this->assertDatabaseHas('budgets', [
        'name' => 'My Budget',
        'amount' => 1000,
        'type' => 'general',
        'user_id' => $user->id,
    ]);

    $budget = Budget::first();
    expect($budget->user_id)->toBe($user->id);
});

it('Creates a budget and redirects with success message', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->post(route('budgets.store'), [
            'name' => 'My Budget',
            'amount' => 1000,
            'type' => 'general',
        ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', '¡Presupuesto creado con éxito!');
});

it('Does not allow unverified users to create a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)
        ->post(route('budgets.store'), [
            'name' => 'My Budget',
            'amount' => 1000,
            'type' => 'general',
        ]);

    $response->assertRedirect(route('verification.notice'));
});

it('Validates amount must be greater than zero', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $response = $this->actingAs($user)
        ->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => 'Budget with Zero Amount',
            'amount' => 0,
            'type' => 'general',
        ]);
    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors(['amount']);
});

it('Validate type must be valid', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $response = $this->actingAs($user)
        ->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => 100,
            'type' => 'not_valid_type',
        ]);
    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors(['type']);
});

it('Accepts valid budget data', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $response = $this->actingAs($user)
        ->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => 100,
            'type' => 'general',
        ]);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('budgets', [
        'name' => 'Boda',
        'amount' => 100,
        'type' => 'general',
        'user_id' => $user->id,
    ]);
});
