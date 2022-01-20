<?php

namespace App\Transformers\User;

use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;
use Illuminate\Support\Facades\URL;

class UserPatientTransformer extends TransformerAbstract
{


	protected $showData;

	public function __construct($showData = true)
	{
		$this->showData = $showData;
	}
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	

	public function transform($user): array
	{
		// dd($user->patient->firstName);
		return [
			'id'=>$user->id,
			'uuid' => $user->udid,
			'initials' => ucfirst($user->patient->initials()),
			'name'=>ucfirst($user->patient->firstName).' '.ucfirst($user->patient->lastName),
			'username' => $user->email,
			'email' => $user->email,
			'nickname' => $user->patient->nickName,
			'gender' => $user->patient->gender->name,
			'age'=>$user->getAgeAttribute($user->patient->dob),
			'dateOfBirth' => date("m/d/Y",strtotime($user->patient->dob)),
			'height' => $user->patient->height,
			'contactNo' => $user->patient->phoneNumber,
			'house_no' => $user->patient->appartment,
			'profile_photo' => (!empty($user->profile_photo))&&(!is_null($user->profile_photo)) ? URL::to('/').'/'.$user->profile_photo : "",
			'city' => $user->patient->city,
			'state' => $user->patient->state->name,
			'country' => $user->patient->country->name,
			'zipCode' => $user->patient->zipCode,
			'roleId' => $this->showData ? fractal()->item($user->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'vital'=>(!empty($user->userFamilyAuthorization)) ? $user->userFamilyAuthorization->vital==0 ? 0 :$user->userFamilyAuthorization->vital : '',
		    'message'=>(!empty($user->userFamilyAuthorization))? $user->userFamilyAuthorization->message==0 ? 0 :$user->userFamilyAuthorization->message : '',
			'emailverified' =>$user->emailVerify ? true : false,
			
		];
	}
}
