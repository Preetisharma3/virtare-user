<?php

namespace App\Services\Api;


use App\Helper;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment\Appointment;
use App\Models\Patient\PatientTimeLine;
use App\Transformers\Appointment\AppointmentTransformer;
use App\Transformers\Appointment\AppointmentDataTransformer;
use App\Transformers\Appointment\AppointmentListTransformer;
use App\Transformers\Appointment\AppointmentSearchTransformer;

class AppointmentService
{

    public function addAppointment($request)
    {
        if (Auth::user()) {

            $input = [
                'udid' => Str::random(10),
                'appointmentTypeId' => $request->appointmentTypeId,
                'startDate' => date("Y-m-d", $request->startDate),
                'startTime' => date("H:i:s", $request->startDate),
                'durationId' => $request->durationId,
                'note' => $request->note,
                'createdBy' => Auth::user()->id,
            ];
            if (Auth::user()->roleId == 4) {
                $patientData = Patient::where('userId', Auth::user()->id)->first();
                $entity = [
                    'staffId' => $request->staffId,
                    'patientId' => $patientData->id,
                ];
            } else {
                $staffData = Staff::where('userId', Auth::user()->id)->first();
                $entity = [
                    'staffId' => $staffData->id,
                    'patientId' => $request->patientId,
                ];
            }
            $data = array_merge($entity, $input);
        } else {
            $input = [
                'udid' => Str::random(10),
                'appointmentTypeId' => $request->appointmentTypeId,
                'startDate' => date("Y-m-d", $request->startDate),
                'startTime' => date("H:i:s", $request->startDate),
                'durationId' => $request->durationId,
                'note' => $request->note,
                'createdBy' => 1,
            ];
            $entity = [
                'staffId' => $request->staffId,
                'patientId' => $request->patientId,
            ];

            $data = array_merge($entity, $input);
        }
        Appointment::create($data);

        $patientData = Patient::where('id', $request->patientId)->first();
        $staffData = Staff::where('id', $request->staffId)->first();
        $timeLine = [
            'patientId' => $patientData->id, 'heading' => 'Appointment', 'title' => 'Appointment for'.' '. $patientData->firstName.' '. $patientData->lastName.' '.'Added with'.' '.$staffData->firstName.' '. $staffData->lastName, 'type' => 1,
            'createdBy' => 1, 'udid' => Str::uuid()->toString()
        ];
        PatientTimeLine::create($timeLine);
        return response()->json(['message' => 'created successfully']);
    }

    public function appointmentList($request)
    {
        $data = Appointment::where([['patientId', auth()->user()->patient->id], ['startDate', '>=', Carbon::today()]])->get();
        $results = Helper::dateGroup($data, 'startDate');
        return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
    }

    public function futureAppointment($request)
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDate', '>=', Carbon::today())->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function newAppointments()
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->latest('createdAt')->take(3)->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function todayAppointment($request)
    {
        if (auth()->user()) {

            $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', auth()->user()->patient->id], ['startDate', Carbon::today()]])->get();
        } else {

            $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDate', Carbon::today())->get();
        }
        return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
    }

    public function appointmentSearch($request)
    {
        $fromDate = date("Y-m-d", $request->fromDate);
        $toDate = date("Y-m-d", $request->toDate);
        $data = DB::select(
            'CALL appointmentList("' . $fromDate . '","' . $toDate . '")',
        );
        return fractal()->collection($data)->transformWith(new AppointmentSearchTransformer())->toArray();
    }
}
