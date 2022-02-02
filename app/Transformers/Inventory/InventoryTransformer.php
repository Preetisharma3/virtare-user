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
            'udid' => $data->udid,
            'deviceType' => $data->deviceType,
            'modelNumber' => $data->modelNumber,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
        ];
    }
}
