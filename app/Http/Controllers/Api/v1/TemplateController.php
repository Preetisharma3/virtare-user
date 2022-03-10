<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\TemplateService;
use App\Services\Api\ExcelGeneratorService;
use App\Services\Api\ExportReportRequestService;

class TemplateController extends Controller
{
   
    public function listTemplate(Request $request)
    {
        return (new TemplateService())->listTemplate($request);  
    } 

    public function createTemplate(Request $request)
    {
        return (new TemplateService())->createTemplate( $request);
    }

    public function updateTemplate(Request $request,$id)
    {
        return (new TemplateService())->updateTemplate( $request,$id);
    }

    public function deleteTemplate(Request $request,$id)
    {
        return (new TemplateService())->deleteTemplate( $request,$id);
    }

    public function templateReport(Request $request,$id)
    {
        if($id)
        {
            $reportType = "template_report";
            $checkReport = ExportReportRequestService::checkReportRequest($id,$reportType);
            if($checkReport){
                ExcelGeneratorService::templateExcelExport($request);
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
