<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\TaskService;
use App\Http\Controllers\Controller;
use App\Services\Api\ExcelGeneratorService;
use App\Services\Api\ExportReportRequestService;

class TaskController extends Controller
{
    public function addTask(request $request)
    {
        return (new TaskService)->addTask($request);
    }

    public function listTask(request $request)
    {
        return (new TaskService)->listTask($request);
    }

    public function taskListEntity(request $request,$entity,$id)
    {
        return (new TaskService)->entityTaskList($request,$entity,$id);
    }

    public function priorityTask(request $request)
    {
        return (new TaskService)->priorityTask($request);
    }

    public function statusTask(request $request)
    {
        return (new TaskService)->statusTask($request);
    }

    public function updateTask(request $request, $id)
    {
        return (new TaskService)->updateTask($request, $id);
    }

    public function taskById($id){
        return (new TaskService)->taskById($id);
    }

    public function deleteTask($id)
    { 
        return (new TaskService)->deleteTask($id);
    }

    public function taskPerStaff(){
        return (new TaskService)->taskPerStaff();
    }

    public function taskPerCategory(){
        return (new TaskService)->taskPerCategory();
    }

    public function taskReport(Request $request,$id)
    {
        if($id)
        {
            $reportType = "task_report";
            $checkReport = ExportReportRequestService::checkReportRequest($id,$reportType);
            if($checkReport){
                ExcelGeneratorService::taskReportExport($request);
            }else{
                return response()->json(['message' => "User not Access to download Report."], 500);
            }
        }
        else
        {
            return response()->json(['message' => "invalid URL."], 500);
        }
    }

    public function taskTotalWithTimeDuration(request $request)
    {
        return (new TaskService)->taskTotalWithTimeDuration($request);
    }

    public function taskCompletedRates(request $request)
    {
        return (new TaskService)->taskCompletedRates($request);
    }
}
