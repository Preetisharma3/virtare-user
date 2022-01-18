<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function patientCount(Request $request)
    {
        return (new DashboardService)->count($request);
    }

    public function activePatients(Request $request)
    {
        return (new DashboardService)->activePatients($request);
    }

    public function inActivePatients(Request $request)
    {
        return (new DashboardService)->inActivePatients($request);
    }

    public function newPatients(Request $request)
    {
        return (new DashboardService)->newPatients($request);
    }

    public function abnormalPatients(Request $request)
    {
        return (new DashboardService)->abnormalPatients($request);
    }

    public function criticalPatients(Request $request)
    {
        return (new DashboardService)->criticalPatients($request);
    }

    public function patientCondition()
    {
        return (new DashboardService)->patientCondition();
    }

    public function staffNetwork()
    {
        return (new DashboardService)->staffNetwork();
    }

    public function staffSpecialization()
    {
        return (new DashboardService)->staffSpecialization();
    }
}
