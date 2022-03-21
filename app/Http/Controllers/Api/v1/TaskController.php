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

    public function taskListEntity(request $request, $entity, $id)
    {
        return (new TaskService)->entityTaskList($request, $entity, $id);
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

    public function taskById(request $request, $id)
    {
        return (new TaskService)->taskById($request, $id);
    }

    public function deleteTask(request $request, $id)
    {
        return (new TaskService)->deleteTask($request, $id);
    }

    public function taskPerStaff(request $request)
    {
        return (new TaskService)->taskPerStaff($request);
    }

    public function taskPerCategory(request $request)
    {
        return (new TaskService)->taskPerCategory($request);
    }

    public function taskReport(Request $request, $id)
    {
        if ($id) {
            $reportType = "task_report";
            $checkReport = ExportReportRequestService::checkReportRequest($id, $reportType);
            if ($checkReport) {
                ExcelGeneratorService::taskReportExport($request);
            } else {
                return response()->json(['message' => "User not Access to download Report."], 500);
            }
        } else {
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
