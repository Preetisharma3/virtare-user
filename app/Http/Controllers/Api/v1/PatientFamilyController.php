<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\UserService;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientFamilyService;

class PatientFamilyController extends Controller
{

  public function createPatientFamily(Request $request,$id=null)
  {
    return (new PatientFamilyService)->patientFamilyCreate($request,$id);
  }

  public function listPatientFamily(Request $request,$id=null)
  {
    return (new PatientFamilyService)->patientFamilyList($request,$id);
  }

 
}
