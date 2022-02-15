<?php

namespace App\Transformers\Task;

use League\Fractal\TransformerAbstract;
use App\Transformers\Task\TaskAssignedTransformer;


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
           'description'=>$data->description,
           'taskStatus'=>$data->taskStatus->name,
           'priority'=>$data->priority->name,
           'category'=>$data->taskCategory  ? fractal()->collection($data->taskCategory)->transformWith(new TaskCategoryTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : array(),
           'dueDate'=>$data->dueDate,
           'assignedTo'=>$data->assignedTo  ? fractal()->collection($data->assignedTo)->transformWith(new TaskAssignedTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() :array(),
           'assignedBy'=>$data->user->email,
           'status'=>$data->isActive? True:False
        ];
      
    }
}
