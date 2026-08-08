<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $this->get(route('shared'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the shared page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('shared'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('shared/Index'));
});
