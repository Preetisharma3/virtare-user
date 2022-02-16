<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\TimeLogService;
use App\Services\Api\TimelineService;

class TimeLogController extends Controller
{
    public function listTimeLog(Request $request,$id=null)
    {
        return (new TimeLogService)->timeLogList($request,$id);
    }

    public function updateTimeLog(Request $request,$id)
    {
        return (new TimeLogService)->timeLogUpdate($request,$id);
    }

    public function deleteTimeLog(Request $request,$id)
    {
        return (new TimeLogService)->timeLogDelete($request,$id);
    }
}
