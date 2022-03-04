<?php

namespace App\Transformers\Role;

use League\Fractal\TransformerAbstract;
 

class RoleListTransformer extends TransformerAbstract
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
            'id' => $data->id,
            'udid' =>$data->udid,
            'name' => $data->roles,
            'description' => $data->roleDescription,
            'roleType' =>$data->roleType->name,
            'status' => $data->isActive,
        ];
      
    }
}
