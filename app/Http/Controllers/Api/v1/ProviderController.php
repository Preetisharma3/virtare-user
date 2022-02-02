<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ProviderService;

class ProviderController extends Controller
{
    
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        return (new ProviderService)->store($request);
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
