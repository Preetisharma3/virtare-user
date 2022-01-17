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
            'deviceType' => $data->deviceTypes->name,
            'modelNumber' => $data->modelNumber,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
        ];
    }
}
