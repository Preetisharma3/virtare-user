<?php

namespace App\Transformers\Communication;

use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class CommunicationTransformer extends TransformerAbstract
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
            'id'=>$data->id,
			'patient'=>$data->patient->firstName,
            'category'=>$data->globalCode->name,
            'send to'=>$data->to,
            'status'=>$data->isActive,
            'staff'=> fractal()->collection($data->staff)->transformWith(new StaffTransformer())->toArray(),
		];
    }
}
