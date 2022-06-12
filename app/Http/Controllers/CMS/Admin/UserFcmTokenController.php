<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Http\Controllers\Controller;

class UserFcmTokenController extends Controller
{
    /**
     * Functionality to send notification.
     *
     */
    public function sendNotification($tokens, $body, $title, $subTitle)
    {
        // $tokens = ["c8VoYuLWQvm3g307GrTIhZ:APA91bE5VEoGaiIsnLE129ZRKFnXjNpzZKc50uyynXh55SHlL3VHkc7wQkJTaizlQ3ZqrW2vXQ0jmhe-kx2gq-C544pzUHqn6U49-Lb7e2DnpgO780ruXH_dZvfBxQs8xwicg4K23rj0"];
        $responseData = [];

        $FCM_SERVER_KEY = 'AAAAP477uM4:APA91bEy2O_QTSr5l0zc6mqOcWQWZ7VYdMTzdoJ5WzG4E82iEaTCqKKIS5Z4Hl7xcB0OF-kj7G5u2_V-JmR7THGujawNZ92pkgfI2GBhNdSQw2eOaUND-skkkzgWrgppxWy9KMl4OqMI';
        $msg = array
        (
            'body' => $body,
            'title' => $title,
            'subtitle' => $subTitle,
        );
        $fields = array
        (
            'registration_ids' => $tokens,
            'notification' => $msg,
            "android" => [
                "notification" => [
                    "sound" => "default"
                ]
            ],
            "apns" => [
                "payload" => [
                    "sound" => "default"
                ]
            ]
        );
        $headers = array
        (
            'Authorization: key=' . $FCM_SERVER_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
//            die('FCM Send Error: ' . curl_error($ch));
            return false;
        }
        $result = json_decode($result, true);
        $responseData['android'] = [
            "result" => $result
        ];
        curl_close($ch);
        // return true;
        return $result;
    }
}
