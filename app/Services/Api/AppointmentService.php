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
use App\Models\Patient\PatientStaff;
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
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function appointmentList($request, $id)
    {
        if (!$id) {
                $data = Appointment::where([['patientId', auth()->user()->patient->id], ['startDateTime', '>=', Carbon::now()->subMinute(30)]])->orderBy('createdAt', 'DESC')->get();
                $results = Helper::dateGroup($data, 'startDateTime');
                return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
        } elseif ($id) {
                $patient = Helper::entity('patient', $id);
                $access = Helper::haveAccess($patient);
                if(!$access){
                    $data = Appointment::where([['patientId', $patient], ['startDateTime', '>=', Carbon::now()->subMinute(30)]])->latest('createdAt')->get();
                    $results = Helper::dateGroup($data, 'startDateTime');
                    return fractal()->collection($results)->transformWith(new AppointmentListTransformer())->toArray();
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
                $patient=Helper::entity('patient',$id);
                $access=Helper::haveAccess($patient);
                if($access){
                    $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['patientId', $patient]])->exists();
                    if ($familyMember == true) {
                        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $patient]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('createdAt', 'DESC')->get();
                    } else {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    }
                } elseif (auth()->user()->roleId == 3) {
                    $staff = PatientStaff::where([['staffId', auth()->user()->staff->id], ['patientId', $patient]])->exists();
                    if ($staff) {
                        $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $patient]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('createdAt', 'DESC')->get();
                    } else {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    }
                } else {
                    $data = Appointment::with('patient', 'staff', 'appointmentType', 'duration')->where([['patientId', $patient]])->whereDate('startDateTime', '=', Carbon::today())->orderBy('createdAt', 'DESC')->get();
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
                $staffs = explode(',',$request->staffId);
                $staff_array = array();
                foreach ($staffs as  $staff) {
                    $staff_id = Helper::entity('staff',trim($staff));
                    array_push($staff_array, $staff_id);
                }
                $staffIdx = json_encode($staff_array);
            }

            $data = DB::select(
                "CALL appointmentList('" . $fromDate . "','" . $toDate . "','" . $staffIdx . "')",
            );
            return fractal()->collection($data)->transformWith(new AppointmentSearchTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function AppointmentConference($request)
    {
        $data = Appointment::whereRaw('conferenceId is not null')->where('startDateTime', '>=', Carbon::now()->subMinute(30))->get();
        return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
    }

    public function AppointmentConferenceId($request, $id)
    {
        $data = Appointment::where([['startDateTime', '>=', Carbon::now()->subMinute(30)], ['conferenceId', $id]])->get();
        return fractal()->collection($data)->transformWith(new AppointmentDataTransformer())->toArray();
    }

    public function appointmentUpdate($request, $id)
    {
        $input = ['updatedBy' => Auth::id(), 'startDateTime' => Helper::date($request->startDateTime)];
        Appointment::where([['patientId', auth()->user()->patient->id],['udid',$id]])->update($input);
        $data = Appointment::where([['patientId', auth()->user()->patient->id],['udid',$id]])->first()->orderBy('createdAt', 'DESC');
        return fractal()->item($data)->transformWith(new AppointmentTransformer())->toArray();
    }

    public function appointmentDelete($request, $id)
    {
        $input = ['deletedBy' => Auth::id(), 'isDelete' => 1,'isActive'=>0];
        Appointment::where([['patientId', auth()->user()->patient->id],['udid',$id]])->update($input);
        Appointment::where([['patientId', auth()->user()->patient->id],['udid',$id],['startDateTime','>=',Carbon::now()->subMinutes(60)]])->delete();
        return response()->json(['message'=>trans('messages.deletedSuccesfully')]);
    }

}
