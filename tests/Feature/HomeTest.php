<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('memories'));

    $response->assertRedirect(route('login'));
});

test('guests can visit the landing page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('authenticated users are redirected from the home page to memories', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertRedirect(route('memories'));
});

test('authenticated users can visit the memories page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('memories'));

    $response->assertOk();
});
