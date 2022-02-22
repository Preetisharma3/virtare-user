<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\PatientGoalService;

class PatientGoalController extends Controller
{
    public function index(Request $request,$id = null,$goalId = null){
        return (new PatientGoalService)->index($request,$id,$goalId);
    }
     
    public function deviceTypeGoal(Request $request,$id = null,$goalId = null){
        return (new PatientGoalService)->deviceTypeGoal($request,$id,$goalId);
    }
}
