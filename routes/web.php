<?php

use Illuminate\Support\Facades\Route;

// WEB UI ROUTES
Route::get('/', [App\Http\Controllers\HomeController::class, 'HomePage'])->name('home');

// Firebase Client-Side Configuration
Route::get('/client-side.js', [App\Http\Controllers\HomeController::class, 'ClientSideFirebaseJS'])->name('env.js');
