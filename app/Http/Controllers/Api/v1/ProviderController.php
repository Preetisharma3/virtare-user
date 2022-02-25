<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ProviderService;

class ProviderController extends Controller
{

    public function index()
    {
        return (new ProviderService)->index();
    }

    public function store(Request $request)
    {
        return (new ProviderService)->store($request);
    }

    public function providerLocationStore(Request $request,$id)
    {
        return (new ProviderService)->providerLocationStore($request,$id);
    }

    public function providerLocationList(Request $request,$id)
    {
        return (new ProviderService)->providerLocationList($request,$id);
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
