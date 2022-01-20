<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class StaffTransformer extends TransformerAbstract
{


    protected $showData;

	public function __construct($showData = true)
	{
		$this->showData = $showData;
	}
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
            'udid'=>$data->udid,
            'userId'=>$data->userId,
			'staff'=>$data->firstName.' '.$data->lastName,
            'type'=>$data->roles->roles,
            'username'=>$data->email,
            'email'=>$data->email,
            'contactNo'=>$data->phoneNumber,
            'id'=>$data->id,
			'uuid' => $data->udid,
			'roleId' => $this->showData ? fractal()->item($data->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'gender'=>$data->gender->name,
			'network'=>$data->network->name,
			'specialization'=>$data->specialization->name,
			'designation'=>$data->designation->name,
		];
    }
}
