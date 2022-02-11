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
use App\Transformers\Appointment\AppointmentTransformer;
use App\Transformers\Appointment\AppointmentDataTransformer;
use App\Transformers\Appointment\AppointmentListTransformer;
use App\Transformers\Appointment\AppointmentSearchTransformer;
use Exception;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;

class AppointmentService
{

    public function addAppointment($request)
    {
        try {
            $input = [
                'udid' => Str::uuid()->toString(),
                'appointmentTypeId' => $request->appointmentTypeId,
                'startDateTime' => date("Y-m-d H:i:s", $request->startDate),
                'durationId' => $request->durationId,
                'note' => $request->note,
                'createdBy' => Auth::user()->id,
            ];
            if (Auth::user()->patient) {
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
            return response()->json(['message' => 'Created Successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function appointmentList($request)
    {
        try {
            $data = Appointment::where([['patientId', auth()->user()->patient->id], ['startDateTime', '>=', Carbon::today()]])->get();
            $results = Helper::dateGroup($data, 'startDateTime');
            return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

  

    public function newAppointments()
    {
        try {
            $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->latest('createdAt')->take(3)->get();
            return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function todayAppointment($request)
    {
        try {
            if (auth()->user()) {
                $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', auth()->user()->patient->id], ['startDateTime', Carbon::today()]])->get();
            } else {
                $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where('startDateTime', Carbon::today())->get();
            }
            return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function appointmentSearch($request)
    {
        try{
        $fromDate='';
        $toDate='';
        $fromDate = date("Y-m-d H:i:s", $request->fromDate);
        if(!empty( $request->toDate)){
            $toDate = date("Y-m-d H:i:s", $request->toDate);
        }
            $data = DB::select(
                'CALL appointmentList("' . $fromDate . '","' . $toDate . '")',
            );
        return fractal()->collection($data)->transformWith(new AppointmentSearchTransformer())->toArray();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
