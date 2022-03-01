<?php

namespace App\Transformers\Provider;

use League\Fractal\TransformerAbstract;
 

class ProviderTransformer extends TransformerAbstract
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
            'name' => $data->name,
            'address'=>$data->address,
            'zipcode'=>$data->zipcode,
            'phoneNumber'=>$data->phoneNumber,
            'countryId'=>$data->countryId,
            'stateId'=>$data->stateId,
            'city'=>$data->city,
            'zipCode'=>$data->zipcode,
            'phoneNumber'=>$data->phoneNumber,
            'tagId'=>$data->tagId,
            'moduleId'=>$data->moduleId,
        ];
      
    }
}
