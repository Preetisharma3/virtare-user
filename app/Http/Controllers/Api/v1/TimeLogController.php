<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\TimeLogService;
use App\Services\Api\TimelineService;
use App\Services\Api\ExcelGeneratorService;
use App\Services\Api\ExportReportRequestService;

class TimeLogController extends Controller
{
    public function listTimeLog(Request $request, $id = null)
    {
        return (new TimeLogService)->timeLogList($request, $id);
    }

    public function updateTimeLog(Request $request, $id)
    {
        return (new TimeLogService)->timeLogUpdate($request, $id);
    }

    public function deleteTimeLog(Request $request, $id)
    {
        return (new TimeLogService)->timeLogDelete($request, $id);
    }

    public function addPatientTimeLog(Request $request, $entityType, $id = null, $timelogId = null)
    {
        return (new TimeLogService)->patientTimeLogAdd($request, $entityType, $id, $timelogId);
    }

    public function listPatientTimeLog(Request $request, $entityType, $id = null, $timelogId = null)
    {
        return (new TimeLogService)->patientTimeLogList($request, $entityType, $id, $timelogId);
    }

    public function deletePatientTimeLog(Request $request, $entityType, $id = null, $timelogId)
    {
        return (new TimeLogService)->patientTimeLogDelete($request, $entityType, $id, $timelogId);
    }

    public function timeLogReport(Request $request,$id){
        if($id)
        {
            $reportType = "patient_time_log_report";
            $checkReport = ExportReportRequestService::checkReportRequest($id,$reportType);
            if($checkReport){
                ExcelGeneratorService::excelTimeLogExport($request);
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
