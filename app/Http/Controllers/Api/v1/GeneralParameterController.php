<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\GeneralParameterService;

class GeneralParameterController extends Controller
{
    
    public function addGeneralParameterGroup(Request $request,$id=null)
    {
        return (new GeneralParameterService)->generalParameterAdd($request,$id);
    }

    public function listGeneralParameterGroup(Request $request,$id=null)
    {
        return (new GeneralParameterService)->generalParameterGroupList($request,$id);
    }

    public function listGeneralParameter(Request $request,$id)
    {
        return (new GeneralParameterService)->generalParameterList($request,$id);
    }


    public function deleteGeneralParameterGroup(Request $request,$id)
    {
        return (new GeneralParameterService)->generalParameterGroupDelete($request,$id);
    }

    public function deleteGeneralParameter(Request $request,$id)
    {
        return (new GeneralParameterService)->generalParameterDelete($request,$id);
    }
    
    public function generalParameterSearch(Request $request)
    {
         return (new GeneralParameterService)->generalParameterSearch($request);
    }

}
