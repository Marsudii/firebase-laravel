<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    public function fcm(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Fcm created successfully',
        ]);
    }
}
