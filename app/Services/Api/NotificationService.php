<?php

namespace App\Services\Api;

use App\Helper;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment\Appointment;
use App\Models\Communication\CallRecord;
use App\Models\Notification\Notification;
use App\Services\Api\PushNotificationService;
use App\Models\Appointment\AppointmentNotification;
use App\Models\Communication\CommunicationCallRecord;
use App\Models\Patient\PatientFlag;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;

class NotificationService
{
    public function appointmentNotification()
    {
        $appointments = DB::select(
            'CALL appointmentListNotification("' . date("Y-m-d H:i:s", time()) . '","' . date("Y-m-d H:i:s", strtotime('+30 minutes')) . '")',
        );
        if (!empty($appointments)) {
            foreach ($appointments as $appointment) {

                $to_time = strtotime($appointment->startTime);
                $from_time = time();
                $minutes =  (int)round(abs($to_time - $from_time) / 60,0);

                $patient = Patient::where('id', $appointment->patientId)->first();
                $userId = $patient->userId;
                $notification = Notification::create([
                    'body' => 'You have a Appointment in '.$minutes.' minutes.',
                    'title' => 'Appointment Reminder',
                    'userId' => $appointment->patientUserId,
                    'isSent' => 0,
                    'entity' => 'Appointment',
                    'referenceId' => $appointment->id,
                    'createdBy' => $appointment->staffUserId,
                ]);
                AppointmentNotification::create([
                    'udid' => Str::random(10),
                    'appointmentId' => $appointment->id,
                    'lastNotification' => 1,
                    'createdBy' => $appointment->staffUserId,
                ]);
            }
        }
    }

    public function appointmentNotificationSend()
    {
        $notifications = DB::select(
            'CALL notificationList("0","")',
        );
        if (!empty($notifications)) {
            foreach ($notifications as $notification) {

                $pushnotification = new PushNotificationService();
                $notificationData = array(
                    "body" => $notification->body,
                    "title" => $notification->title,
                    "type" => $notification->entity,
                    "typeId" => $notification->referenceId,
                );
                $pushnotification->sendNotification([$notification->userId], $notificationData);

                Notification::where('id', $notification->id)->update(['isSent' => '1']);
            }
        }
    }

    public function appointmentConfrence()
    {
        $toDate = Helper::date(strtotime('+5 minutes'));

        $fromDate = Helper::date(time());

        $appointments = DB::select(
            'CALL appointmentList("' . $fromDate . '","' . $toDate . '","")',
        );
        if (!empty($appointments)) {
            foreach ($appointments as $appointment) {
                if (empty($appointment->conferenceId) || is_null($appointment->conferenceId)) {

                    $staffId = Helper::entity('staff', $appointment->staff_id);
                    $patentId = Helper::entity('patient', $appointment->patient_id);

                    $patient = Patient::where('id', $patentId)->first();
                    $userId = $patient->userId;

                    $staff = Staff::where('id', $staffId)->first();
                    $staffUserId = $staff->userId;

                    $notification = Notification::create([
                        'body' => 'Your Appointment going to start please join.',
                        'title' => 'Appointment Reminder',
                        'userId' => $userId,
                        'isSent' => 0,
                        'entity' => 'Confrence',
                        'referenceId' => 'CONF' . $appointment->id,
                        'createdBy' => $staffUserId,
                    ]);
                    Appointment::where('id', $appointment->id)->update(['conferenceId' => 'CONF' . $appointment->id]);

                    $input = [
                        'patientId' => $patient->id,
                        'callStatusId' => 47,
                        'udid' => Str::uuid()->toString(),
                        'referenceId' => 'CONF' . $appointment->id,
                        'entityType' => 'conferenceCall'
                    ];
                    $comm = CommunicationCallRecord::create($input);
                    $call = ['udid' => Str::uuid()->toString(), 'createdBy' => $patient->id, 'communicationCallRecordId' => $comm->id, 'staffId' => $staffId];
                    CallRecord::create($call);
                }
            }
        }
        $confrence =  Appointment::whereNotNull('conferenceId')->get();
        // Helper::updateFreeswitchConfrence($confrence);
    }

    public function appointmentConfrenceIdUpdate()
    {
        $fromDate = date('Y-m-d H:i:s', strtotime('-2 hours'));
        return DB::select(
            'CALL appointmentConferenceIdUpdate("' . $fromDate . '")',
        );
    }

    public function removeNewPatientFlag()
    {
        PatientFlag::whereHas('patient', function ($query) {
            $query->where('createdAt', '>=', Carbon::now()->subDay());
        })->delete();

        return response()->json(['message'=>trans('messages.deletedSuccesfully')]);
    }
}
