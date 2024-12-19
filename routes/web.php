<?php

use App\Http\Controllers\Admin\ItemController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/items', ItemController::class);

    Route::get('/item-list', [ItemController::class, 'list']);

    Route::post('/items/{item}/toggle-publish', [ItemController::class, 'changePublishStatus']);
});
