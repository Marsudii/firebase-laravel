<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// GROUPED API ROUTES V1
Route::prefix('v1')->group(function () {
    // API V1 (FCM) routes
    Route::post('/firebase/fcm', [App\Http\Controllers\Api\FcmController::class, 'fcm'])->name("fcm");
    Route::post('/firebase/fcm-multicast', [App\Http\Controllers\Api\FcmController::class, 'fcmMulticast'])->name("fcm-multicast");

    // API V1 (Topic) routes
    Route::post('/firebase/topic-subscribe', [App\Http\Controllers\Api\TopicController::class, 'topicSubscribe'])->name("topic-subscribe");
    Route::post('/firebase/topic-unsubscribe', [App\Http\Controllers\Api\TopicController::class, 'topicUnsubscribe'])->name("topic-unsubscribe");
    Route::post('/firebase/topic-publish', [App\Http\Controllers\Api\TopicController::class, 'topicPublish'])->name("topic-publish");
});

