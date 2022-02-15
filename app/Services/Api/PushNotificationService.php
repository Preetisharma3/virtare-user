<?php

namespace App\Services\Api;

use App\FCM;
use Exception;
use App\Helper;
use GuzzleHttp\Client;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification\Notification;
use App\Http\Requests\Notification\NotificationRequest;
use App\Transformers\Notification\NotificationListTransformer;

class PushNotificationService
{
    public function sendNotification(array $users,$data)
    {
        try {
            $deviceToken = array();
            $currentUser = Auth::id();
            if(empty($currentUser)){
                $currentUser = 1;
            }
            foreach ($users as $userId) {
                $user = User::find($userId);
                array_push($deviceToken,$user->deviceToken);

                $notification = new NotificationRequest();
                $notification->userId = $user->id;
                $notification->type = $data['type'];
                $notification->body = $data['body'];
                $notification->title = $data['title'];
                $notification->createdBy = $currentUser;
                $notification->save();
            }
            $fcm = new FCM();
            $fcm->deviceId($deviceToken);
            $fcm->notifications([
                "title" => $data['title'],
                "body" => $data['body']
            ]);
            $fcm->data([
                "type" => $data['type']
            ]);
            $fcm->send();
            return response()->json(['message' => trans('messages.notification')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function showNotification($request)
    {
        try {
            $notification = Notification::where('userId', Auth::id())->orderBy("id","DESC")->get();
            $notificationUnread = Notification::where('userId', Auth::id())->where('isRead','0')->count();
            if($notificationUnread > 0 && empty($request->count)){
                Notification::where('userId', Auth::id())->update(['isRead'=>'1']);
            }
            $notification = Helper::dateGroup($notification, 'createdAt');
            $notificationList = fractal()->collection($notification)->transformWith(new NotificationListTransformer)->toArray();
            if(empty($request->count)){
                return array_merge($notificationList,['count'=>$notificationUnread]);
            }else{
                return ['count'=>$notificationUnread];
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
    
    public function ios_token($deviceToken)
    {
        $server_key = env("FCM_SERVER_KEY");

        $headers = [
            'Authorization' => 'key=' . $server_key,
            'Content-Type'  => 'application/json',
        ];
        $fields = [
            'application' => "com.aiets.iccrpm",
            'sandbox' => env("FCM_SANDBOX"),
            'apns_tokens' => array($deviceToken)
        ];

        $client = new Client();
        try {
            $request = $client->post("https://iid.googleapis.com/iid/v1:batchImport", [
                'headers' => $headers,
                "body" => json_encode($fields),
            ]);
            $response = json_decode($request->getBody()->getContents(),true);
            return $response['results'][0]['registration_token'];
        } catch (Exception $e) {
            return $e;
        }
    }
}
