<?php

use App\Models\User;

test('guests can visit the landing page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('authenticated users are redirected from the home page to memories', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertRedirect(route('memories'));
});
