<?php

use Illuminate\Support\Facades\Route;

// WEB UI ROUTES
Route::get('/', [App\Http\Controllers\HomeController::class, 'HomePage'])->name('home');
