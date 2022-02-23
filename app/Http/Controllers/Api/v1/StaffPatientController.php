<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Services\Api\StaffService;
use App\Http\Controllers\Controller;
use App\Models\Patient\PatientStaff;
use App\Transformers\Patient\PatientTransformer;

class StaffPatientController extends Controller
{
    public function patientList($id = null){
        return (new StaffService)->patientList( $id);
    }

    public function appointmentList($id = null){
        return (new StaffService)->appointmentList( $id);
    }
}
