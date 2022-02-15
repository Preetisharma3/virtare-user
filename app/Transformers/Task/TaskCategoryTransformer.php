<?php

namespace App\Transformers\Task;

use League\Fractal\TransformerAbstract;


class TaskCategoryTransformer extends TransformerAbstract
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
           'id'=>$data->id,
           'taskid'=>$data->taskId,
           'taskCategory'=>$data->taskCategory->name,
        ];
      
    }
}
