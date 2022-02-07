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
            'name' => $data->roles,
            'roleDescription' => $data->roleDescription,
            'roleType' => $data->roleType,
            'masterLogin'=>$data->masterLogin,
            'status' => $data->isActive,
        ];
      
    }
}
