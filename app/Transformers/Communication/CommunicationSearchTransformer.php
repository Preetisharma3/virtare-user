<?php

namespace App\Transformers\Communication;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class CommunicationSearchTransformer extends TransformerAbstract
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
                'id'=>$data->communicationId,
                'from'=>$data->staffFromName,
                'type'=>$data->messageName,
                'to'=> $data->entity=='patient'?$data->patientName:$data->staffReference,
                'category'=>$data->categoryName,
                'priority'=>$data->priorityName,
                'createdAt'=>date('M j, Y - h:i A', strtotime($data->communicationCreateDate)),
            ];
        
            
    }
}
