<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientStaffService;

class PatientStaffController extends Controller
{
    public function assignStaff(Request $request,$id=null,$patientStaffId=null){
        return (new PatientStaffService)->assignStaffToPatient($request,$id,$patientStaffId);
    }

    public function getAssignStaff(Request $request,$id=null,$satffId=null){
        return (new PatientStaffService)->getAssignStaffToPatient($request,$id,$satffId);
    }

    public function deleteAssignStaff(Request $request,$id=null,$patientStaffId){
        return (new PatientStaffService)->deleteAssignStaffToPatient($request,$id,$patientStaffId);
    }
}
