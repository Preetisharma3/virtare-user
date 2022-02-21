<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientStaff;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Patient\PatientStaffTransformer;

class PatientStaffService
{
    public function assignStaffToPatient($request, $id, $patientStaffId)
    {
        try {
            if (!$patientStaffId) {
                $patientId = Patient::where('udid', $id)->first();
                $input = ['udid' => Str::uuid()->toString(), 'patientId' => $patientId->id, 'staffId' => $request->input('staff'), 'createdBy' => Auth::id()];
                PatientStaff::create($input);
                $user=PatientStaff::where('udid', $patientStaffId)->first();
                $message=['message' => trans('messages.created_succesfully')];
                $userdata = fractal()->item($user)->transformWith(new PatientStaffTransformer())->toArray();
            } else {
                $input = ['staffId' => $request->input('staff'), 'updatedBy' => Auth::id()];
                PatientStaff::where('udid', $patientStaffId)->update($input);
                $user=PatientStaff::where('udid', $patientStaffId)->first();
                $message=['message' => trans('messages.updated_succesfully')];
                $userdata = fractal()->item($user)->transformWith(new PatientStaffTransformer())->toArray();
            }
               $endData = array_merge($message, $userdata);
               return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAssignStaffToPatient($request, $id, $patientStaffId)
    {
        try {
            if ($patientStaffId) {
                $getPatient = PatientStaff::where('udid', $patientStaffId)->with('patient', 'staff')->first();
                return fractal()->item($getPatient)->transformWith(new PatientStaffTransformer())->toArray();
            } else {
                $patientId = PatientStaff::where('udid', $id)->with('patient', 'staff')->first();
                $getPatient = PatientStaff::where('patientId', $patientId->id)->get();
                return fractal()->collection($getPatient)->transformWith(new PatientStaffTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteAssignStaffToPatient($request, $id, $patientStaffId)
    {
        try {
            $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
            PatientStaff::where('udid', $patientStaffId)->update($input);
            PatientStaff::where('udid', $patientStaffId)->delete();
            return response()->json(['message' => trans('messages.deleted_succesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
