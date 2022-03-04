<?php

namespace App\Services\Api;

use App\Helper;
use Exception;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientStaff;
use App\Models\Staff\Staff;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Patient\PatientStaffTransformer;

class PatientStaffService
{
    public function assignStaffToPatient($request, $id, $patientStaffId)
    {
        try {
            $staff = Helper::entity('staff', $request->input('staff'));
            if (!$patientStaffId) {
                $patient = Helper::entity('patient', $id);
                $input = ['udid' => Str::uuid()->toString(), 'patientId' => $patient, 'staffId' => $staff, 'createdBy' => Auth::id()];
                PatientStaff::create($input);
                return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
            } else {
                $input = ['staffId' => $staff, 'updatedBy' => Auth::id()];
                PatientStaff::where('udid', $patientStaffId)->update($input);
                return response()->json(['message' => trans('messages.updatedSuccesfully')], 200);
            }
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
                $patient = Helper::entity('patient', $id);
                $getPatient = PatientStaff::where('patientId', $patient)->orderBy('createdAt', 'DESC')->get();
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
            return response()->json(['message' => trans('messages.deletedSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
