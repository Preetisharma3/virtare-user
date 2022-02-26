<?php

namespace App\Services\Api;


use Exception;
use App\Helper;
use Carbon\Carbon;
use App\Models\Note\Note;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment\Appointment;
use App\Models\Patient\PatientTimeLine;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Appointment\AppointmentTransformer;
use App\Transformers\Appointment\AppointmentDataTransformer;
use App\Transformers\Appointment\AppointmentListTransformer;
use App\Transformers\Appointment\AppointmentSearchTransformer;

class AppointmentService
{

    public function addAppointment($request, $id)
    {
        try {
            $input = [
                'udid' => Str::uuid()->toString(),
                'appointmentTypeId' => $request->appointmentTypeId,
                'startDateTime' => date("Y-m-d H:i:s", $request->startDate),
                'durationId' => $request->durationId,
                'createdBy' => Auth::user()->id,
            ];
            if (Auth::user()->patient) {
                $patientData = Patient::where('userId', Auth::user()->id)->first();
                $staff = Staff::where('udid', $request->staffId)->first();
                $entity = [
                    'staffId' => $staff->id,
                    'patientId' => $patientData->id,
                ];
            } elseif (auth()->user()->staff) {
                $staff = Staff::where('udid', $request->staffId)->first();
                $entity = [
                    'staffId' => $staff->id,
                    'patientId' => $request->patientId,
                ];
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                $staff = Staff::where('udid', $request->staffId)->first();
                    $entity = [
                        'staffId' => $staff->id,
                        'patientId' => $id,
                    ];
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
            }
            $data = array_merge($entity, $input);
            $appointment=Appointment::create($data);
            $note=['createdBy'=>Auth::id(),'note'=>$request->input('note'),'udid'=>Str::uuid()->toString(),'entityType'=>'appointment','referenceId'=>$appointment->id];
            Note::create($note);
            $patientData = Patient::where('id', $data['patientId'])->first();
            $staffData = Staff::where('id', $data['staffId'])->first();
            $timeLine = [
                'patientId' => $patientData->id, 'heading' => 'Appointment', 'title' => 'Appointment for' . ' ' . $patientData->firstName . ' ' . $patientData->lastName . ' ' . 'Added with' . ' ' . $staffData->firstName . ' ' . $staffData->lastName, 'type' => 1,
                'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
            ];
            PatientTimeLine::create($timeLine);
            return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function appointmentList($request, $id)
    {
        if (!$id) {
            if ($request->latest) {
                $patientId = Patient::where('udid', $request->id)->first();
                $data = Appointment::where([['patientId', $patientId->id], ['startDateTime', '>=', Carbon::today()]])->latest()->get();
                return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
            } else {
                $data = Appointment::where([['patientId', auth()->user()->patient->id], ['startDateTime', '>=', Carbon::today()]])->get();
                $results = Helper::dateGroup($data, 'startDateTime');
                return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
            }
        } elseif ($id) {
            $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $id]])->exists();
            if ($familyMember == true) {
                if ($request->latest) {
                    $patientId = Patient::where('udid', $request->id)->first();
                    $data = Appointment::where([['patientId', $id], ['startDateTime', '>=', Carbon::today()]])->latest()->first();
                    return fractal()->item($data)->transformWith(new AppointmentDataTransformer())->toArray();
                } else {
                    $data = Appointment::where([['patientId', $id], ['startDateTime', '>=', Carbon::today()]])->get();
                    $results = Helper::dateGroup($data, 'startDateTime');
                    return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
                }
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
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

    public function todayAppointment($request, $id)
    {
        try {
            if (!$id) {
                if (auth()->user()->patient) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', auth()->user()->patient->id]])->whereDate('startDateTime', '=', Carbon::today())->get();
                } elseif (auth()->user()->staff) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['staffId', auth()->user()->staff->id]])->whereDate('startDateTime', '=', Carbon::today())->get();
                }
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $id]])->exists();
                if ($familyMember == true) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $id]])->whereDate('startDateTime', '=', Carbon::today())->get();
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
            }
            return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function appointmentSearch($request)
    {
        try {
            $fromDate = time();
            $toDate = '';
            if (!empty($request->toDate)) {
                $toDate = date("Y-m-d H:i:s", $request->toDate);
            }
            if (!empty($request->fromDate)) {
                $fromDate = date("Y-m-d H:i:s", $request->fromDate);
            }
            $data = DB::select(
                'CALL appointmentList("' . $fromDate . '","' . $toDate . '")',
            );
            return fractal()->collection($data)->transformWith(new AppointmentSearchTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
