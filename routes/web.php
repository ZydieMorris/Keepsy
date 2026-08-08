<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function (Request $request) {
    return $request->user()
        ? redirect()->route('memories')
        : Inertia::render('landing/Index');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('memories', 'memories/Index')->name('memories');
    Route::inertia('shared', 'shared/Index')->name('shared');
    Route::inertia('people', 'people/Index')->name('people');
});

require __DIR__.'/settings.php';
