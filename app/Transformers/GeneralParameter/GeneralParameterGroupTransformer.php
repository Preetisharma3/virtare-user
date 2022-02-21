<?php

namespace App\Transformers\GeneralParameter;

use League\Fractal\TransformerAbstract;

class GeneralParameterGroupTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    { 
        return [
            'id'=>$data->udid,
            'name'=>$data->name,
            'deviceType'=>(!empty($data->deviceType->name))?$data->deviceType->name:'',
            'deviceTypeId'=>(!empty($data->deviceType->id))?$data->deviceType->id:'',
            'generalparameter'=> fractal()->collection($data->generalParameter)->transformWith(new GeneralParameterTransformer())->toArray()
        ];
    }
}
