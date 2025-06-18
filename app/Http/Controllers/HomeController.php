<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function HomePage()
    {
        return view('app.Home.Page');
    }

    public function ClientSideFirebaseJS()
    {
        $envFirebaseClientSide = [
            'apiKey' => env('FIREBASE_API_KEY'),
            'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
            'projectId' => env('FIREBASE_PROJECT_ID'),
            'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
            'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
            'appId' => env('FIREBASE_APP_ID'),
        ];
        // base64 encode the Firebase config
        $envFirebaseClientSide = base64_encode(json_encode($envFirebaseClientSide));
        return response()->view('utils.JavascriptClientSide', compact('envFirebaseClientSide'))
            ->header('Content-Type', 'application/javascript');
    }
}
