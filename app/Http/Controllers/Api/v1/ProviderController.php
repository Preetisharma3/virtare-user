<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ProviderService;

class ProviderController extends Controller
{

    public function index($id = null)
    {
        return (new ProviderService)->index($id);
    }

    public function store(Request $request)
    {
        return (new ProviderService)->store($request);
    }

    public function providerLocationStore(Request $request, $id)
    {
        return (new ProviderService)->providerLocationStore($request, $id);
    }

    public function editLocation($id, $locationId = null)
    {
        return (new ProviderService)->editLocation($id, $locationId);
    }

    public function updateProvider(Request $request, $id)
    {
        return (new ProviderService)->updateProvider($request, $id);
    }

    public function updateLocation(Request $request, $id, $locationId)
    {
        return (new ProviderService)->updateLocation($request, $id, $locationId);
    }

    public function deleteProviderLocation($id, $locationId = null){
        return (new ProviderService)->deleteProviderLocation($id, $locationId);
    }
}
