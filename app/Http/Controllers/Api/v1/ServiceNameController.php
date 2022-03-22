<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\ServiceNameService;
use Illuminate\Http\Request;

class ServiceNameController extends Controller
{

    public function listService(Request $request, $id = NULL)
    {
        return (new ServiceNameService)->listService($request, $id);
    }

    public function createService(Request $request)
    {
        return (new ServiceNameService)->createService($request);
    }

    public function updateService(Request $request, $id)
    {
        return (new ServiceNameService)->updateService($request, $id);
    }

    public function deleteService(Request $request, $id)
    {
        return (new ServiceNameService)->deleteService($request, $id);
    }
}
