<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API ROUTES
Route::post('/firebase/topic', [App\Http\Controllers\Api\TopicController::class, 'topic'])->name("send-topic");
Route::post('/firebase/fcm', [App\Http\Controllers\Api\FcmController::class, 'fcm'])->name("send-fcm");
