<?php

namespace App\Transformers\Inventory;

use App\Models\Vital\VitalField;
use League\Fractal\TransformerAbstract;
use App\Transformers\Vital\VitalFieldTransformer;

class InventoryListTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        if(empty($data)){
            return [];
        }
        return [
            'id' => $data->id,
            'deviceModelId'=>$data->deviceModelId,
            'deviceType' => (!empty($data->model->deviceType->name)) ? $data->model->deviceType->name : $data->deviceType,
            'modelNumber' => $data->modelNumber ? $data->modelNumber : $data->model->modelName,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
            'status' => $data->isActive ? True : False,
        ];
    }
}
