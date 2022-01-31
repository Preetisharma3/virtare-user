<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\TimelineService;

class TimelineController extends Controller
{
    public function patientTotal(Request $request)
    {
        return (new TimelineService)->patientTotal($request);
    }

    public function appointmentTotal(Request $request)
    {
        return (new TimelineService)->appointmentTotal($request);
    }
}
