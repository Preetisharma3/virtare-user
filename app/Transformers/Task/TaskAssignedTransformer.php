<?php

namespace App\Transformers\Task;

use League\Fractal\TransformerAbstract;


class TaskAssignedTransformer extends TransformerAbstract
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
        if($data->entityType == 'staff'){
        return[
           'id'=>$data->assigned ? $data->assigned->id:'',
           'entityType'=>$data->entityType,
           'taskId'=>$data->taskId,
           'name'=>ucfirst(@$data->assigned->firstName).' '.ucfirst(@$data->assigned->lastName)
        ];
    }elseif($data->entityType == 'patient'){
        return[
            'id'=>$data->patient->id,
            'entityType'=>$data->entityType,
            'taskId'=>$data->patient->taskId,
            'name'=>ucfirst($data->patient->firstName).' '.ucfirst($data->patient->lastName)
         ];
    }
      
    }
}
