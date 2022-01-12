<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientService;

class PatientController extends Controller
{

  public function createPatient(Request $request)
  {
    return (new PatientService)->patientCreate($request);
  }

  public function createPatientCondition(Request $request,$id)
  {
    return (new PatientService)->patientConditionCreate($request,$id);
  }
}
