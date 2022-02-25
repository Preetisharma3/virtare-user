<?php

namespace App\Services\Api;

use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Notification\Notification;
use App\Models\Appointment\AppointmentNotification;

class NotificationService
{
    public function appointmentNotification($request)
    {
        $appointments = DB::select(
            'CALL appointmentListNotification("'.time().'","'.strtotime('+30 minutes').'")',
        );
        if (!empty($appointments)) {
            foreach ($appointments as $appointment) {
                $patient = Patient::where('id', $appointment->patientId)->first();
                $userId = $patient->userId;
                $notification = Notification::create([
                    'body' => 'Your Appointment is Scehduled.',
                    'title' => 'Appointment Reminder',
                    'userId' => $appointment->patientUserId,
                    'entity'=>'Appointment',
                    'referenceId' => $appointment->id,
                    'createdBy' => $appointment->staffId,
                ]);
                
                AppointmentNotification::create([
                    'udid' => Str::random(10),
                    'appointmentId' => $appointment->id,
                    'lastNotification' => 1,
                    'createdBy' => $appointment->staffId,,
                ]);
            }
            /*$deviceToken = $request->deviceToken;
            $deviceType = $request->deviceType;
            if ($deviceType == 'ios') {
                $pushNotification = new PushNotificationService();
                $deviceToken = $pushNotification->ios_token($deviceToken);
            }*/

            return response()->json(['message' => trans('messages.notification')], 200);
        } else {
            return response()->json(['message' => trans('Appointments are not Found')], 200);
        }
    }
}
