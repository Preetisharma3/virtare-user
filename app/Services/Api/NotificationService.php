<?php

namespace App\Services\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment\Appointment;
use App\Models\Notification\Notification;
use App\Transformers\Dummy\DummyTransformer;
use App\Models\Appointment\AppointmentNotification;

class NotificationService
{
    public function notif($request)
    {
        $data = DB::select(
            'CALL notificationList()',
        );
        foreach ($data as $new) {
            $patient = Patient::where('id', $new->patientId)->get();
            foreach ($patient as $patientInfo) {
                $userId = $patientInfo->userId;
                $notification = Notification::create([
                        'body' => 'Your Appointment is Scehduled.',
                        'title' => 'Appointment Reminder',
                        'userId' => $userId,
                        'notificationTypeId' => 39,
                        'createdBy'=>1,
                    ]);
            }
            AppointmentNotification::create([
                'udid'=>Str::random(10),
                'appointmentId'=>$new->id,
                'lastNotification'=>1,
                'createdBy'=>1,
            ]);
        }
        $deviceToken = $request->deviceToken;
      $deviceType = $request->deviceType;
      if($deviceType == 'ios'){
          $pushNotification = new PushNotificationService();
          $deviceToken = $pushNotification->ios_token($deviceToken);
      }

        // return response()->json(['message' => trans('messages.notification')], 200);
    }
}
