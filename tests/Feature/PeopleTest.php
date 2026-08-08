<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $this->get(route('people'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the people page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('people'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('people/Index'));
});
