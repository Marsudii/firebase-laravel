<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FcmController extends Controller
{
    private $firebaseService;


    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function fcm(Request $request)
    {
        // VALIDATE REQUEST
        $validator = Validator::make($request->all(), [
            'notification_title' => 'required|string|max:255',
            'notification_text' => 'required|string|max:255',
            'token' => 'required|string',
        ]);
        // VALIDATION FAILED
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Bad request',
                'errors' => $validator->errors(),
            ], 400);
        }

        // CAPTURE DATA FROM REQUEST
        $title = $request->input('notification_title');
        $text = $request->input('notification_text');
        $token = $request->input('token');

        // CALL SERCVICE FIREBASE
        $result = $this->firebaseService->sendToDevice($token, $title, $text);

        // RETURN RESPONSE FAILED
        if (!$result['success']) {
            return response()->json([
                'status' => $result['success'],
                'message' => $result['message'],
                'errors' => $result['errors'],
            ], 500);
        }

        // RETURN RESPONSE SUCCESS
        return response()->json([
            'status' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }



    public function fcmMulticast(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'notification_title' => 'required|string|max:255',
            'notification_text' => 'required|string|max:255',
            'tokens' => 'required|array|min:1',
            'tokens.*' => 'string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Bad request',
                'errors' => $validator->errors(),
            ], 400);
        }
        // Prepare the FCM payload
        $title = $request->input('notification_title');
        $text = $request->input('notification_text');
        $tokens = $request->input('tokens');
        // Send the notification using FirebaseService
        $result = $this->firebaseService->sendToDevices($tokens, $title, $text);
        // Check the result and return appropriate response
        if (!$result['success']) {
            return response()->json([
                'status' => $result['success'],
                'message' => 'Failed to send notification',
                'errors' => $result['errors'],
            ], 500);
        }
        return response()->json([
            'status' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
}
