<?php

namespace App\Transformers\Communication;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


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
            return [
                'id'=>$data->id,
                'from'=>$data->staff->firstName,
                'type'=>$data->type->name,
                'to'=>$data->entityType=='patient'?$data->patient->firstName:$data->staff->firstName,
                'category'=>$data->globalCode->name,
                'priority'=>$data->priority->name,
                'createdAt'=>date('M j, Y - h:i A', strtotime($data->createdAt)),
            ];
    }
}
