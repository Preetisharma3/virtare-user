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
            'patientInventoryId' => $data->id,
            'patientInventoryudid'=>$data->udid,
            'patientId'=>$data->patientId,
            'inventoryId'=>$data->inventoryId,
            'inventoryUdid'=>$data->inventory->udid,
            'deviceType' => $data->inventory->deviceTypes->name,
            'modelNumber' => $data->inventory->modelNumber,
            'serialNumber' => $data->inventory->serialNumber,
            'macAddress' => $data->inventory->macAddress,
            'isAdded'=>$data->isAdded,
            'isActive'=>$data->isActive
        ];
    }
}
