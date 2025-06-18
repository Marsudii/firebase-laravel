<?php

namespace App\Http\Services;

use Kreait\Firebase\Factory;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = config('firebase.json_credentials');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to a single device
     */
    public function sendToDevice(string $token, string $title, string $text): array
    {
        try {
            $notification = Notification::create($title, $text);
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->toToken($token);
            $response_firebase = $this->messaging->send($message);
            return [
                'success' => true,
                'message' => 'Notification sent successfully',
                'data' => $response_firebase
            ];

        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to send notification',
                'errors' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToDevices(array $tokens, string $title, string $text): array
    {
        if (count($tokens) > 500) {
            throw new \InvalidArgumentException('Too many tokens. Maximum 500 tokens allowed per multicast message.');
        }
        try {
            $notification = Notification::create($title, $text);
            $message = CloudMessage::new()
                ->withNotification($notification);

            if (!empty($data)) {
                $message = $message->withData($data);
            }
            $report = $this->messaging->sendMulticast($message, $tokens);
            $result = [
                'success' => true,
                'message' => 'Success to send multicast notification',
                'data' => [
                    'sent' => $report->successes()->count(),
                    'failed' => $report->failures()->count(),
                    'total' => $report->count(),
                ]
            ];

            if ($report->hasFailures()) {
                $result['success'] = false;
                $result['message'] = 'Failed to send multicast notification';
                foreach ($report->failures()->getItems() as $failure) {
                    $result['errors'][] = [
                        'token' => $failure->target()->value(),
                        'error' => $failure->error()->getMessage()
                    ];
                }
            }
            return $result;
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to send multicast notification',
                'errors' => $e->getMessage()
            ];
        }
    }



    /**
     * Send notification to a topic
     */
    public function sendToTopic(string $topic, string $title, string $text): array
    {
        try {
            $notification = Notification::create($title, $text);
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification);

            $publish = $this->messaging->send($message);

            return [
                'success' => true,
                'message' => 'Topic notification sent successfully',
                'data' => $publish,
            ];
        } catch (\Throwable $e) {

            return [
                'success' => false,
                'message' => 'Failed to send topic notification',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Subscribe devices to a topic
     */
    public function subscribeToTopic(string $topic, array $tokens): array
    {
        try {
            $result = $this->messaging->subscribeToTopic($topic, $tokens);
            $invalidTokens = [];
            foreach ($result as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $token => $status) {
                        if ($status === 'INVALID_ARGUMENT') {
                            $invalidTokens[] = [
                                'token' => $token,
                                'error' => $status,
                            ];
                        }
                    }
                } elseif ($value === 'INVALID_ARGUMENT') {
                    $invalidTokens[] = [
                        'token' => $key,
                        'error' => $value,
                    ];
                }
            }
            if (!empty($invalidTokens)) {
                return [
                    'success' => false,
                    'message' => 'Some tokens failed to subscribe to topic',
                    'errors' => $invalidTokens,
                ];
            }
            return [
                'success' => true,
                'message' => 'Devices subscribed to topic',
                'data' => $result,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to subscribe devices to topic',
                'errors' => $e->getMessage()
            ];
        }
    }

    /**
     * Unsubscribe devices from a topic
     */
    public function unsubscribeFromTopic(string $topic, array $tokens): array
    {
        try {
            $result = $this->messaging->unsubscribeFromTopic($topic, $tokens);
            $invalidTokens = [];
            foreach ($result as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $token => $status) {
                        if ($status === 'INVALID_ARGUMENT') {
                            $invalidTokens[] = [
                                'token' => $token,
                                'error' => $status,
                            ];
                        }
                    }
                } elseif ($value === 'INVALID_ARGUMENT') {
                    $invalidTokens[] = [
                        'token' => $key,
                        'error' => $value,
                    ];
                }
            }
            if (!empty($invalidTokens)) {
                return [
                    'success' => false,
                    'message' => 'Some tokens failed to subscribe to topic',
                    'errors' => $invalidTokens,
                ];
            }
            return [
                'success' => true,
                'message' => 'Devices unsubscribed to topic',
                'data' => $result,
            ];
        } catch (\Throwable $e) {
            Log::error('FCM topic unsubscription error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to unsubscribe devices from topic',
                'errors' => $e->getMessage()
            ];
        }
    }
}
