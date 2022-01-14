<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientInventoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->id,
            'inventory' => $data->inventoryId,
            'patientId' => $data->patientId,
            'deviceType' => $data->deviceType->name,
            'modelNumber' => $data->modelNumber,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
            'deviceTime' => $data->deviceTime,
            'serverTime' => $data->serverTime,
        ];
    }
}
