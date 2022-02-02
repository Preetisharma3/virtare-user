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
            'id' => $data->id,
            'patientId' => $data->patientId,
            'deviceType' => $data->deviceTypes->name,
            'modelNumber' => $data->modelNumber,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
            'deviceTime' => $data->deviceTime,
            'serverTime' => $data->serverTime,
            'isAdded'=>$data->isAdded,
            'inventory' => fractal()->item($data->inventory)->transformWith(new InventoryTransformer())->toArray(),
        ];
    }
}
