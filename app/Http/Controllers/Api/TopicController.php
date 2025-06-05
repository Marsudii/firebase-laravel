<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopicController extends Controller
{

    public function topic(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Topic created successfully',
        ]);
    }
}
