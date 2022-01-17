<?php

namespace App\Transformers\Task;

use League\Fractal\TransformerAbstract;
 

class TaskPriorityTransformer extends TransformerAbstract
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
    public function transform($data)
    {
        return[ 
            'taskPriority'=> $data->priority->name,
            'count'=>$data->count
        ];
      
    }
}
