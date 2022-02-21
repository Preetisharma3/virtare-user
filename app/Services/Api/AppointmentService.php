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
use App\Models\Patient\PatientFamilyMember;
use App\Models\Patient\PatientTimeLine;
use App\Transformers\Appointment\AppointmentTransformer;
use App\Transformers\Appointment\AppointmentDataTransformer;
use App\Transformers\Appointment\AppointmentListTransformer;
use App\Transformers\Appointment\AppointmentSearchTransformer;
use Exception;

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
                'note' => $request->note,
                'createdBy' => Auth::user()->id,
            ];
            if (Auth::user()->patient) {
                $patientData = Patient::where('userId', Auth::user()->id)->first();
                $entity = [
                    'staffId' => $request->staffId,
                    'patientId' => $patientData->id,
                ];
            } elseif (auth()->user()->staff) {
                $patient = Patient::where('udid', $request->input('patientId'))->first();
                $entity = [
                    'staffId' => $request->staffId,
                    'patientId' => $patient->id,
                ];
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                    $patient = Patient::where('udid', $id)->first();
                    $entity = [
                        'staffId' => $request->staffId,
                        'patientId' => $patient->id,
                    ];
                } else {
                    return response()->json(['message' => trans('messages.unauthorized')],401);
                }
            }
            $data = array_merge($entity, $input);
            Appointment::create($data);
            $patientData = Patient::where('id', $data['patientId'])->first();
            $staffData = Staff::where('id', $data['staffId'])->first();
            $timeLine = [
                'patientId' => $patientData->id, 'heading' => 'Appointment', 'title' => 'Appointment for' . ' ' . $patientData->firstName . ' ' . $patientData->lastName . ' ' . 'Added with' . ' ' . $staffData->firstName . ' ' . $staffData->lastName, 'type' => 1,
                'createdBy' => 1, 'udid' => Str::uuid()->toString()
            ];
            PatientTimeLine::create($timeLine);
            return response()->json(['message' => trans('messages.created_succesfully')],200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function appointmentList($request, $id)
    {
        if (!$id) {
            if ($request->latest) {
                $patientId = Patient::where('udid', $request->id)->first();
                $data = Appointment::where([['patientId', $patientId->id], ['startDateTime', '>=', Carbon::today()]])->latest()->first();
                return fractal()->item($data)->transformWith(new AppointmentDataTransformer())->toArray();
            } else {
                $data = Appointment::where([['patientId', auth()->user()->patient->id], ['startDateTime', '>=', Carbon::today()]])->orderBy('createdAt', 'DESC')->get();
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
                    $data = Appointment::where([['patientId', $id], ['startDateTime', '>=', Carbon::today()]])->orderBy('createdAt', 'DESC')->get();
                    $results = Helper::dateGroup($data, 'startDateTime');
                    return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
                }
            } else {
                return response()->json(['message' => trans('message.unauthorized')],200);
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
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', auth()->user()->patient->id]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('createdAt', 'DESC')->get();
                } elseif (auth()->user()->staff) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['staffId', auth()->user()->staff->id]])->whereDate('startDateTime', '=', Carbon::today())->order('createdAt', 'DESC')->get();
                }
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $id]])->exists();
                if ($familyMember == true) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $id]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('createdAt', 'DESC')->get();
                } else {
                    return response()->json(['message' => trans('message.unauthorized')],401);
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
