<?php

namespace App\Transformers\Notification;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class NotificationTransformer extends TransformerAbstract
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
        return [
           
               "body"=>$data->body,
               "title"=>$data->title,
               "type"=>@$data->entity,
               "type_id"=>@$data->referenceId,
               "Isread"=>$data->isRead,
               "time"=>date('H:i a',strtotime($data->createdAt)),

            //    "created_user"=>fractal()->item($data->created_user)->transformWith(new UserTransformer(true))->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray(),
               
        ];
    }
}
