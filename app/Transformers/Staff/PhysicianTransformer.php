<?php

namespace App\Transformers\Staff;

use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;
use Illuminate\Support\Facades\URL;

class PhysicianTransformer extends TransformerAbstract
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
            "user_id"=>$data->userId,
            'uuid'=>$data->udid,
            'name'=>$data->name,
            'username'=>$data->user->email,
            'email'=>$data->user->email,
            "first_login"=> 0,
            "nickname"=> $data->nickname ? $data->nickname : '',
            'age'=>$data->getAgeAttribute($data->date_of_birth) ? $data->getAgeAttribute($data->date_of_birth) : '',
            'date_of_birth' => date("m/d/Y",strtotime($data->dob)) ? date("m/d/Y",strtotime($data->dob)) :'',
			'height' => $data->height ? $data->height :'',
			'contact_no' => $data->phoneNumber,
			'house_no' => $data->house_no,
			'profile_photo' => (!empty($data->profile_photo))&&(!is_null($data->profile_photo)) ? URL::to('/').'/'.$data->profile_photo : "",
			'city' => $data->city,
			'state' => $data->state,
			'country' => $data->country,
			'zip_code' => $data->zip_code,
            'is_primary'=>$data->sameAsReferal,
            'role' => $this->showData ? fractal()->item($data->user->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
            'vital'=>(!empty($data->userFamilyAuthorization)) ? $data->userFamilyAuthorization->vital==0 ? 0 :$data->userFamilyAuthorization->vital : '',
		    'message'=>(!empty($data->userFamilyAuthorization))? $data->userFamilyAuthorization->message==0 ? 0 :$data->userFamilyAuthorization->message : '',
            'availability' => $data->availability ? $data->availability:array(),
            'notes' =>$data->notes ? $data->notes:array(),
            'today_appointment' => $data->appointment ? $data->appointment:array(),
		];
    }
}