<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PushNotificationService;
use App\Http\Requests\Notification\NotificationSendRequest;

class PushNotificationController extends Controller
{
    public function notificationShow(Request $request)
    {
        return (new PushNotificationService)->showNotification($request);
    }

    // public function index(){
    //     $users = User::whereNotNull('deviceToken')->where('deviceToken',"!=","")->get();
    //     return view('notification',compact('users'));
    // }
    // public function send(NotificationSendRequest $request){
        
    //     $data = array(
    //                     "title" => $request->title,
    //                     "body" =>$request->message,
    //                     "type" =>$request->type,
    //     );
    //     $notification = (new PushNotificationService)->send_notification($request->user,$data);
    //     return redirect()->to('notification')->with('message', 'Send Successfully');
    // }
}
