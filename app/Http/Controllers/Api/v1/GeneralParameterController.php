<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ExcelGeneratorService;
use App\Services\Api\GeneralParameterService;
use App\Services\Api\ExportReportRequestService;

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
    
    public function generalParameterReport(Request $request,$id)
    {
        if($id)
        {
            $reportType = "general_parameter_report";
            $checkReport = ExportReportRequestService::checkReportRequest($id,$reportType);
            if($checkReport){
                ExcelGeneratorService::generalParameterExcelExport($request);
            }else{
                return response()->json(['message' => "User not Access to download Report."], 500);
            }
        }
        else
        {
            return response()->json(['message' => "invalid URL."], 500);
        }
    }
}
