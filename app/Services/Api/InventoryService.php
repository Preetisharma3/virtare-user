<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use App\Transformers\Device\DeviceModelTransformer;
use App\Transformers\Inventory\InventoryTransformer;
use App\Transformers\Inventory\InventoryListTransformer;



class InventoryService
{
    public function store($request)
    {
        try {
            $input = $request->only(['deviceModelId', 'serialNumber', 'macAddress', 'isActive']);
            $otherData = [
                'udid' => Str::random(10),
                'createdBy' => 1
            ];
            $data = json_encode(array_merge($input, $otherData));
            DB::select(
                "CALL createInventories('" . $data . "')"
            );

            return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function index($request)
    {
        try {
            $isAvailable = $request->isAvailable;
            $deviceType = $request->deviceType;
            $data = DB::select('CALL inventoryList("' . $isAvailable . '","' . $deviceType . '")');
            return fractal()->collection($data)->transformWith(new InventoryListTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
            DB::select('CALL updateInventory("' . $id . '","' . $deviceType . '","' . $modelNumber . '","' . $serialNumber . '","' . $macAddress . '","' . $isActive . '","' . $updatedBy . '")');
            $message  = ['message' => trans('messages.updatedSuccesfully')];
            $newData = Inventory::where('id', $id)->first();
            $data =  fractal()->item($newData)->transformWith(new InventoryTransformer())->toArray();
            $response = array_merge($message, $data);
            return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::select('CALL deleteInventory(' . $id . ')');
            return response()->json(['message' => trans('messages.deletedSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getModels($request)
    {
        try {
            $deviceType = $request->deviceType;
            $data = DB::select('CALL deviceModelList("' . $deviceType . '")');
            return fractal()->collection($data)->transformWith(new DeviceModelTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
