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

    public function staffNetwork(Request $request)
    {
        return (new DashboardService)->staffNetwork($request);
    }

    public function staffSpecialization(Request $request)
    {
        return (new DashboardService)->staffSpecialization($request);
    }
}
