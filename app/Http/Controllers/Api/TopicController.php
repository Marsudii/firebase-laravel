<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\FirebaseService;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function topicSubscribe(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
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
        $topic = $request->input('topic');
        $tokens = $request->input('tokens');

        // Subscribe the tokens to the topic
        $result = $this->firebaseService->subscribeToTopic($topic, $tokens);

        if (!$result['success']) {
            return response()->json([
                'status' => $result['success'],
                'message' => $result['message'],
                'errors' => $result['errors'],
            ], 500);
        }

        return response()->json([
            'status' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function topicUnsubscribe(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
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
        $topic = $request->input('topic');
        $tokens = $request->input('tokens');
        // Unsubscribe the tokens from the topic
        $result = $this->firebaseService->unsubscribeFromTopic($topic, $tokens);

        if (!$result['success']) {
            return response()->json([
                'status' => $result['success'],
                'message' => $result['message'],
                'errors' => $result['errors'],
            ], 500);
        }

        return response()->json([
            'status' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }

    public function topicPublish(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'notification_title' => 'required|string|max:255',
            'notification_text' => 'required|string|max:255',
        ]);
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
        $topic = $request->input('topic');

        // CALL SERVICE FIREBASE
        $result = $this->firebaseService->sendToTopic($topic, $title, $text);

        // RETURN RESPONSE FAILED
        if (!$result['success']) {
            return response()->json([
                'status' => $result['success'],
                'message' => $result['message'],
                'errors' => $result['error'],
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Publish topic successfully',
            'data' => $result['data']
        ]);
    }
}
