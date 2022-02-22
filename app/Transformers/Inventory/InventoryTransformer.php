<?php

namespace App\Transformers\Inventory;

use App\Models\Vital\VitalField;
use League\Fractal\TransformerAbstract;
use App\Transformers\Vital\VitalFieldTransformer;

class InventoryTransformer extends TransformerAbstract
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
            'deviceTypeId'=>$data->model->deviceType->id,
            'deviceType' => (!empty($data->model->deviceType->name)) ? $data->model->deviceType->name : $data->deviceType,
            'modelNumber' => $data->modelNumber ? $data->modelNumber : $data->model->modelName,
            'serialNumber' => $data->serialNumber,
            'macAddress' => $data->macAddress,
            'status' => $data->isActive ? True : False,
            'vitalField'=> $data->model->deviceType ?  fractal()->collection($data->model->deviceType->vitalFieldType)->transformWith(new VitalFieldTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray():'',
        ];
    }
}
