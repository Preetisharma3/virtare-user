<?php

namespace App\Transformers\Communication;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class CommunicationCountTransformer extends TransformerAbstract
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
    public function transform($data): array
    {
        
        return [
            'text'=>$data['text'],
            'count'=>$data['count'],
            'backgroundColor'=> $data['backgroundColor'],
            'textColor'=>$data['textColor']
		];
    }
}
