<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Note\Note;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientStaff;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Note\NoteTransformer;
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
                return response()->json(['message' => 'Created Successfully'], 200);
            } else {
                $input = ['staffId' => $request->input('staff'), 'updatedBy' => Auth::id()];
                PatientStaff::where('udid', $patientStaffId)->update($input);
                return response()->json(['message' => 'updated Successfully'], 200);
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
                $patientId = PatientStaff::where('udid', $id)->with('patient', 'staff')->first();
                dd($patientId);
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
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
