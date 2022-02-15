<?php

namespace App\Transformers\Task;

use League\Fractal\TransformerAbstract;


class TaskTransformer extends TransformerAbstract
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
           'title'=>$data->title,
           'taskStatus'=>$data->taskStatus->name,
           'priority'=>$data->priority->name, 
           'taskCategory'=>$data->taskCategoryId,
           'assignedTo'=>$data->assignedTo,
           'dueDate'=>strtotime($data->dueDate),
           'assignedBy'=>$data->user->email,
           'status'=>$data->isActive? True:False
        ];
      
    }
}
