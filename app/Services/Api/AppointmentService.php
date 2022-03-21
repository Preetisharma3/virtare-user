<?php

namespace App\Services\Api;


use Exception;
use App\Helper;
use Carbon\Carbon;
use App\Models\Note\Note;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Library\ErrorLogGenerator;
use Illuminate\Support\Facades\DB;
use App\Models\Patient\PatientStaff;
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
            $startDateTime = Helper::date($request->input('startDate'));
            $input = [
                'udid' => Str::uuid()->toString(),
                'appointmentTypeId' => $request->appointmentTypeId,
                'startDateTime' => $startDateTime,
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
                $staff = Helper::entity('staff', $request->staffId);
                $patient = Helper::entity('patient', $request->patientId);
                $entity = [
                    'staffId' => $staff,
                    'patientId' => $patient,
                ];
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                    $staff = Helper::entity('staff', $request->staffId);
                    $patient = Helper::entity('patient', $id);
                    $entity = [
                        'staffId' => $staff,
                        'patientId' => $patient,
                    ];
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
            }
            $data = array_merge($entity, $input);
            $existence = DB::select(
                "CALL appointmentExist('" . $staff . "','" . $startDateTime . "')",
            );
            foreach($existence as $exists){
            if ($exists->isExist == false) {
                $appointment = Appointment::create($data);
                $note = ['createdBy' => Auth::id(), 'note' => $request->input('note'), 'udid' => Str::uuid()->toString(), 'entityType' => 'appointment', 'referenceId' => $appointment->id];
                Note::create($note);
                $patientData = Patient::where('id', $data['patientId'])->first();
                $staffData = Staff::where('id', $data['staffId'])->first();
                $timeLine = [
                    'patientId' => $patientData->id, 'heading' => 'Appointment', 'title' => 'Appointment for' . ' ' . $patientData->firstName . ' ' . $patientData->lastName . ' ' . 'Added with' . ' ' . $staffData->firstName . ' ' . $staffData->lastName, 'type' => 1,
                    'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
                ];
                PatientTimeLine::create($timeLine);
                return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
            } else {
                return response()->json(['message' => 'Appointment already exist!']);
            }
        }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function appointmentList($request, $id)
    {
        try {
            if (!$id) {
                $data = Appointment::where([['patientId', auth()->user()->patient->id], ['startDateTime', '>=', Carbon::now()->subMinute(30)]])->orderBy('startDateTime', 'ASC')->get();
                $results = Helper::dateGroup($data, 'startDateTime');
                return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
            } elseif ($id) {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    if(auth()->user()->roleId==3){
                        $data = Appointment::where([['staffId',auth()->user()->staff->id],['patientId', $patient], ['startDateTime', '>=', Carbon::now()->subMinute(30)]])->orderBy('startDateTime', 'ASC')->get();
                    }else{
                        $data = Appointment::where([['patientId', $patient], ['startDateTime', '>=', Carbon::now()->subMinute(30)]])->orderBy('startDateTime', 'ASC')->get();
                    }
                    $results = Helper::dateGroup($data, 'startDateTime');
                    return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }    
    }

    public function newAppointments($request)
    {
        try {
            $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration') ->whereRaw('conferenceId != "" OR conferenceId IS NOT NULL')->orderBy('startDateTime', 'ASC')->take(3)->get();
            return fractal()->collection($data)->transformWith(new AppointmentTransformer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function todayAppointment($request, $id)
    {
        try {
            if (!$id) {
                if (auth()->user()->patient) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', auth()->user()->patient->id]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('startDateTime', 'ASC')->get();
                } elseif (auth()->user()->staff) {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['staffId', auth()->user()->staff->id]])->whereDate('startDateTime', '=', Carbon::today())->order('startDateTime', 'ASC')->get();
                }
            } elseif ($id) {
                $patient = Helper::entity('patient', $id);
                $notAccess = Helper::haveAccess($patient);
                if (!$notAccess) {
                    $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $patient]])->exists();
                    if ($familyMember == true) {
                        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $patient]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('startDateTime', 'ASC')->get();
                    } else {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    }
                } elseif (auth()->user()->roleId == 3) {
                    $staff = PatientStaff::where([['staffId', auth()->user()->staff->id], ['patientId', $patient]])->exists();
                    if ($staff) {
                        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $patient]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('startDateTime', 'ASC')->get();
                    } else {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    }
                } else {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $patient]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('startDateTime', 'ASC')->get();
                }
            }
            return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function appointmentSearch($request)
    {
        try {
            $staffIdx = '';
            $fromDate = time();
            $toDate = '';
            if (!empty($request->toDate)) {
                $toDateFormate = Helper::date($request->input('toDate'));
                $toDate = $toDateFormate;
            }
            if (!empty($request->fromDate)) {
                $fromDateFormate = Helper::date($request->input('fromDate'));
                $fromDate = $fromDateFormate;
            }
            $staffIdx = '';
            $staffs = '';
            if (!empty($request->staffId) && $request->staffId != 'undefined') {
                $staffs = explode(',', $request->staffId);
                $staff_array = array();
                foreach ($staffs as  $staff) {
                    $staff_id = Helper::entity('staff', trim($staff));
                    array_push($staff_array, $staff_id);
                }
                $staffIdx = json_encode($staff_array);
            }

            $data = DB::select(
                "CALL appointmentList('" . $fromDate . "','" . $toDate . "','" . $staffIdx . "')",
            );
            return fractal()->collection($data)->transformWith(new AppointmentSearchTransformer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function AppointmentConference($request)
    {
        $data = Appointment::whereRaw('conferenceId is not null')->where('startDateTime', '>=', Carbon::now()->subMinute(30))->get();
        return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
    }

    public function AppointmentConferenceId($request, $id)
    {
        try{
            $data = Appointment::where([['startDateTime', '>=', Carbon::now()->subMinute(30)], ['conferenceId', $id]])->get();
            return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function appointmentUpdate($request, $id)
    {
        try{
            $appointment = Appointment::where([['patientId', auth()->user()->patient->id], ['udid', $id]])->first();

            $existence = DB::select(
                "CALL appointmentExist('" . $appointment['staffId'] . "','" . Helper::date($request->startDateTime) . "')",
            );
            foreach($existence as $exists){
                if ($exists->isExist == false) {

                    $input = ['updatedBy' => Auth::id(), 'startDateTime' => Helper::date($request->startDateTime)];
                    Appointment::where([['patientId', auth()->user()->patient->id], ['udid', $id]])->update($input);
                    $data = Appointment::where([['patientId', auth()->user()->patient->id], ['udid', $id]])->orderBy('startDateTime', 'ASC')->first();
                    return fractal()->item($data)->transformWith(new AppointmentTransformer())->toArray();
                }else {
                    return response()->json(['message' => 'Appointment already exist!']);
                }
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function appointmentDelete($request, $id)
    {
        try{
            $input = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            Appointment::where([['patientId', auth()->user()->patient->id], ['udid', $id]])->update($input);
            Appointment::where([['patientId', auth()->user()->patient->id], ['udid', $id], ['startDateTime', '>=', Carbon::now()->subMinutes(60)]])->delete();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }
}
