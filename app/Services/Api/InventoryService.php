<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Transformers\Inventory\InventoryTransformer;



class InventoryService
{
    public function store($request)
    {
        try {
            $udid = Str::random(10);
            $deviceType = $request->deviceType;
            $modelNumber = $request->modelNumber;
            $serialNumber = $request->serialNumber;
            $macAddress = $request->macAddress;
            $isActive = $request->isActive;
            $createdBy = 1;
            DB::raw('CALL createInventories(?,?,?,?,?,?),(' . $udid . ',' . $deviceType . ',' . $modelNumber . ',' . $serialNumber . ',' . $macAddress . ',' . $isActive . ',' . $createdBy . ')');
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $data = DB::select('CALL inventoryList()');
        return fractal()->collection($data)->transformWith(new InventoryTransformer())->toArray();
    }

    public function update($request, $id)
    {
        try {
            $deviceType = $request->deviceType;
            $modelNumber = $request->modelNumber;
            $serialNumber = $request->serialNumber;
            $macAddress = $request->macAddress;
            $isActive = $request->isActive;
            $updatedBy = 1;
            DB::select('CALL updateInventory(?,?,?,?,?,?,?),(' . $id . ',' . $deviceType . ',' . $modelNumber . ',' . $serialNumber . ',' . $macAddress . ',' . $isActive . ',' . $updatedBy . ')');
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        DB::select('CALL deleteInventory(' . $id . ')');
        return response()->json(['message' => 'deleted successfully'], 200);
    }
}
