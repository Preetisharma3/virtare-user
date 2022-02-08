<?php

namespace App\Transformers\Inventory;

use League\Fractal\TransformerAbstract;

class InventoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    { 
        return [
            'id' => $data->id,
            'deviceType' => (!empty($data->model->deviceType->name))?$data->model->deviceType->name:$data->deviceType,
            'modelNumber' => $data->modelNumber?$data->modelNumber:$data->model->modelName,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
        ];
    }
}
