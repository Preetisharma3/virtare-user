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
}
