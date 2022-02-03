<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\Inventory\InventoryTransformer;

class PatientInventoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->patientInventoryId,
            'patientId' => $data->patientId,
            'isAdded'=>$data->isAdded,
            'status'=>$data->isActive==1?'Active':'Inactive',
            'inventoryId'=>$data->inventoryId,
            'deviceType' => $data->deviceType,
            'modelNumber' => $data->modelNumber,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
        ];
    }
}
