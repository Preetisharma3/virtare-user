<?php

namespace App\Services\Api;

use App\Helper;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Notification\Notification;
use App\Models\Appointment\AppointmentNotification;
use App\Models\Appointment\Appointment;
use App\Services\Api\PushNotificationService;
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
                    'isSent' => 0,
                    'entity'=>'Appointment',
                    'referenceId' => $appointment->id,
                    'createdBy' => $appointment->staffUserId,
                ]);
                Appointment::where('id',$appointment->id)->update(['conferenceId'=>'CONF'.$appointment->id]);
                AppointmentNotification::create([
                    'udid' => Str::random(10),
                    'appointmentId' => $appointment->id,
                    'lastNotification' => 1,
                    'createdBy' => $appointment->staffUserId,
                ]);
            }
        } 
    } 
    public function appointmentNotificationSend($request)
    {
        $notifications = DB::select(
            'CALL notificationList("0","")',
        );
        if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                
                $pushnotification = new Pushnotification();
                $notificationData = array(
                    "body" =>$notification->body,
                    "title" =>$notification->title,
                    "type" =>$notification->entity,
                    "typeId" =>$notification->referenceId,
                );
                $pushnotification->sendNotification([$notification->userId],$notificationData);
            }
            
        }
    }
    public function appointmentConfrence($request)
    {
            $toDate = Helper::date(strtotime('+5 minutes'));
                
            $fromDate = Helper::date(time);
                
            $appointments = DB::select(
                'CALL appointmentList("' . $fromDate . '","' . $toDate . '")',
            );
            if (!empty($appointments)) {
                foreach ($appointments as $appointment) {
                    $staffId = Helper::entity('staff',$appointment->staff_id);
                    $patentId = Helper::entity('patient',$appointment->patient_id);

                    $patient = Patient::where('id', $appointment->patientId)->first();
                    $userId = $patient->userId;
                    $notification = Notification::create([
                        'body' => 'Your Appointment is Scehduled.',
                        'title' => 'Appointment Reminder',
                        'userId' => $patentId,
                        'isSent' => 0,
                        'entity'=>'Confrence',
                        'referenceId' => 'CONF'.$appointment->id,
                        'createdBy' => $staffId,
                    ]);
                    Appointment::where('id',$appointment->id)->update(['conferenceId'=>'CONF'.$appointment->id]);
                    
                }
            }
            $confrence =  Appointment::whereNotNull('conferenceId')->get();
            updateFreeswitchConfrence($confrence);
    }
