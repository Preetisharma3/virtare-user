<?php

namespace App\Transformers\Provider;

use League\Fractal\TransformerAbstract;
 

class ProviderLocationTransformer extends TransformerAbstract
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
            'locationName'=>$data->locationName,
            'phoneNumber'=>$data->phoneNumber,
            'noOfLocation'=>$data->numberOfLocations,
            'address'=>$data->locationAddress
        ];
      
    }
}
