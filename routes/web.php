<?php

use App\Http\Controllers\MemoriesController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SharedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function (Request $request) {
    return $request->user()
        ? redirect()->route('memories')
        : Inertia::render('landing/Index');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('memories', [MemoriesController::class, 'index'])->name('memories');
    Route::post('memories', [MemoriesController::class, 'store'])->name('memories.store');
    Route::patch('memories/{photo}', [MemoriesController::class, 'update'])->name('memories.update');
    Route::delete('memories/{photo}', [MemoriesController::class, 'destroy'])->name('memories.destroy');
    Route::get('shared', [SharedController::class, 'index'])->name('shared');
    Route::get('people', [PeopleController::class, 'index'])->name('people');
});

require __DIR__.'/settings.php';
