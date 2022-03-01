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
            'noOfLocations'=>$data->numberOfLocations,
            'address'=>$data->locationAddress,
            'state'=>$data->stateId,
            'city'=>$data->city,
            'zipCode'=>$data->zipCode,
            'phoneNumber'=>$data->phoneNumber,
            'email'=>$data->email,
            'websiteUrl'=>$data->websiteUrl,
        ];
      
    }
}
