<?php

namespace App\Transformers\Communication;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;
use Carbon\Carbon;

class CommunicationTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data): array
    {
        if($data->entityType=='patient'){
           return [
                'id'=>$data->id,
                'from'=>$data->staff->firstName,
                'type'=>$data->type->name,
                'to'=>$data->patient->firstName,
                'category'=>$data->globalCode->name,
                'priority'=>$data->priority->name,
                'createdAt'=>Carbon::parse($data->createdAt)->timestamp,
            ];
        }
        else{
            return [
                'id'=>$data->id,
                'from'=>$data->staff->firstName,
                'type'=>$data->type->name,
                'to'=>$data->staffs->firstName,
                'category'=>$data->globalCode->name,
                'priority'=>$data->priority->name,
                'createdAt'=>Carbon::parse($data->createdAt)->timestamp,
            ];
        }
            
    }
}
