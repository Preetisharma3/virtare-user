<?php

namespace App\Services\Api;

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
            if (!$patientStaffId) {
                $patientId = Patient::where('udid', $id)->first();
                $staffId = Staff::where('udid', $request->input('staff'))->first();
                $input = ['udid' => Str::uuid()->toString(), 'patientId' => $patientId->id, 'staffId' => $staffId->id, 'createdBy' => Auth::id()];
                PatientStaff::create($input);
                return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
            } else {
                $staffId = Staff::where('udid', $request->input('staff'))->first();
                $input = ['staffId' => $staffId->id, 'updatedBy' => Auth::id()];
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
                $patientId = Patient::where('udid', $id)->first();
                $getPatient = PatientStaff::where('patientId', $patientId->id)->orderBy('createdAt', 'DESC')->get();
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
