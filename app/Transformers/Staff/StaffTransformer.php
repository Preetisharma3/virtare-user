<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;
use App\Transformers\Staff\StaffNoteTransformer;
use App\Transformers\Staff\StaffAvailabilityTransformer;
use App\Transformers\Staff\BookappointmentValueTransformer;


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
            'user_id'=>$data->userId,
            'title'=>$data->firstName,
            'summary'=>$data->summary ? $data->summary :'',
			'name'=>$data->firstName.' '.$data->lastName,
            'expertise'=>$data->designation->name ? $data->designation->name :'',
            'is_primary'=>0,
            'type'=>$data->roles->roles,
            'uuid'=>$data->udid,
            'type'=>$data->roles->roles,
            'username'=>$data->email,
            'email'=>$data->email,
            'first_login'=>0,
            'nickname'=>$data->nickname ? $data->nickname :'',
			'gender'=>$data->gender->name,
            'contact_no'=>$data->phoneNumber,
            'profile_photo'=>$data->profile_photo ? $data->profile_photo:'',
            'network'=>$data->network->name,
            'specialization'=>$data->specialization->name,
            'createdAt'=>$data->createdAt,
            'status'=>$data->isActive ? 'Active':'Inactive',
			'designation'=>$data->designation->name,
			'role' => $this->showData ? fractal()->item($data->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
            'vital'=>(!empty($data->userFamilyAuthorization)) ? $data->userFamilyAuthorization->vital==0 ? 0 :$data->userFamilyAuthorization->vital : '',
		    'message'=>(!empty($data->userFamilyAuthorization))? $data->userFamilyAuthorization->message==0 ? 0 :$data->userFamilyAuthorization->message : '',
            'availability' => $data->availability ? $data->availability:array(),
            'notes' =>$data->notes ? $data->notes:array(),
            'today_appointment' => $data->appointment ? $data->appointment:array(),
		];
    }
}
