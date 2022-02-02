<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(request $request)
    {
        return (new InventoryService)->index($request);
    }

    public function store(Request $request)
    {
        return (new InventoryService)->store($request);
    }

    public function update(Request $request, $id)
    {
       return (new InventoryService)->update($request, $id);
    }

    public function destroy($id)
    {
        return (new InventoryService)->destroy($id);
    }
}