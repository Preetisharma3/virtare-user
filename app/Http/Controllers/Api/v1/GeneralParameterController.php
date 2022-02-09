<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\GeneralParameterService;

class GeneralParameterController extends Controller
{
    
    public function addGeneralParameterGroup(Request $request)
    {
        return (new GeneralParameterService)->generalParameterAdd($request);
    }

    public function listGeneralParameterGroup(Request $request,$id=null)
    {
        return (new GeneralParameterService)->generalParameterGroupList($request,$id);
    }

    public function updateGeneralParameter(Request $request,$id)
    {
        return (new GeneralParameterService)->generalParameterUpdate($request,$id);
    }

    public function deleteGeneralParameterGroup(Request $request,$id)
    {
        return (new GeneralParameterService)->generalParameterGroupDelete($request,$id);
    }

    public function deleteGeneralParameter(Request $request,$id)
    {
        return (new GeneralParameterService)->generalParameterDelete($request,$id);
    }
    
}
