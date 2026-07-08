<?php

use App\Models\Budget;
use App\Models\User;
use App\Policies\BudgetPolicy;

it('allows deleting a budget owned by the user', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create(['user_id' => $user->id]);

    $policy = new BudgetPolicy();

    expect($policy->delete($user, $budget))->toBeTrue();
});

it('denies deleting a budget owned by another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $budget = Budget::factory()->create(['user_id' => $otherUser->id]);

    $policy = new BudgetPolicy();

    expect($policy->delete($user, $budget))->toBeFalse();
});
