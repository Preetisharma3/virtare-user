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
            'id' => $data->udid,
            'deviceType' => (!empty($data->deviceTypes->name))?$data->deviceTypes->name:$data->deviceType,
            'modelNumber' => $data->modelNumber,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
        ];
    }
}
