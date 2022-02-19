<?php

namespace App\Transformers\GeneralParameter;

use League\Fractal\TransformerAbstract;

class GeneralParameterTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    { 
        return [
            'id' => $data->udid,
            'udid'=>$data->udid,
            'generalParameterGroup'=>$data->generalParameterGroup->name,
            'type'=>$data->vitalFieldId,
            'vitalFieldName'=>$data->vitalField->name,
            'highLimit'=>$data->highLimit,
            'lowLimit'=>$data->lowLimit
        ];
    }
}
