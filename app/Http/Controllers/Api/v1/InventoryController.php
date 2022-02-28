<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(request $request,$id=NULL)
    {
        if(!empty($id))
        {
            return (new InventoryService)->geVentoryById($id);
        }
        else
        {
            return (new InventoryService)->index($request);
        }
        
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

    public function getModels(Request $request){
        return (new InventoryService)->getModels($request);
    }
}
