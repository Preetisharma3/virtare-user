<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\VitalService;
use App\Http\Controllers\Controller;

class VitalController extends Controller
{

  public function listVitalTypeField(Request $request, $id = null)
  {
    return (new VitalService)->VitalTypeFieldList($request, $id);
  }
}
