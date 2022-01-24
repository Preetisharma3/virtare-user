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
            'type'=>$data->type->name,
			'patient'=>$data->patient->firstName,
            'category'=>$data->globalCode->name,
            'priority'=>$data->priority->name,
            'status'=>$data->isActive ? 'completed' : 'pending',
            'staff'=> fractal()->collection($data->staff)->transformWith(new StaffTransformer())->toArray(),
            'createdAt'=>date('M j, Y - h:i A', strtotime($data->createdAt)),
		];
    }
}
