<?php

namespace App\Services\Api;


use App\Helper;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment\Appointment;
use App\Transformers\Appointment\AppointmentTransformer;
use App\Transformers\Appointment\AppointmentListTransformer;

class AppointmentService
{

    public function addAppointment($request)
    {
        $input = [
            'udid' => Str::random(10),
            'providerLocationId' => $request->providerLocationId,
            'appointmentTypeId' => $request->appointmentTypeId,
            'startDate' => $request->startDate,
            'startTime' => $request->startTime,
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
        Appointment::create($data);
        return response()->json(['message' => 'created successfully']);
    }

    public function appointmentList($request)
    {
        $providerLocationId = $request->providerLocationId;
        if(auth()->user()->roleId == 4){
        $data = Appointment::where([['patientId', auth()->user()->patient->id ], ['providerLocationId', $providerLocationId]])->where('startDate', '>=', Carbon::now()->toDateString())->orderBy('startDate')->get();
        }else{
            $data = Appointment::where([['staffId',auth()->user()->staff->id],['providerLocationId', $providerLocationId]])->where('startDate', '>=', Carbon::now()->toDateString())->orderBy('startDate')->get();
        }
        $results = Helper::dateGroup($data, 'startDate');
        return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
    }

    public function futureAppointment($request)
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDate', '>', Carbon::today())->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function newAppointments()
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->latest('createdAt')->take(3)->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function todayAppointment($request)
    {
        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDate', Carbon::today())->get();
        return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
    }
}
