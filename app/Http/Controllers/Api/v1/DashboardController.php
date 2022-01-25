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

    public function staffNetwork()
    {
        return (new DashboardService)->staffNetwork();
    }

    public function staffSpecialization()
    {
        return (new DashboardService)->staffSpecialization();
    }

    public function patientCountMonthly()
    {
        return (new DashboardService)->patientCountMonthly();
    }

    public function appointmentCountMonthly()
    {
        return (new DashboardService)->appointmentCountMonthly();
    }
}
