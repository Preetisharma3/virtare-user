<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\StaffService;
use App\Http\Controllers\Controller;

class StaffPatientController extends Controller
{
    public function patientList($id = null){
        return (new StaffService)->patientList( $id);
    }

    public function appointmentList(Request $request,$id = null){
        return (new StaffService)->appointmentList($request, $id);
    }

    public function patientAppointment(Request $request,$id = null){
        return (new StaffService)->patientAppointment( $request,$id);
    }
}
