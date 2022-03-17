<?php

namespace App\Transformers\User;

use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;
use Illuminate\Support\Facades\URL;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Patient\PatientFamilyMemberTransformer;
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
		return [
			'id'=>$user->id,
			'uuid' => $user->udid,
			'sipId' => "UR".$user->id,
			// 'initials' => ucfirst($user->patient->initials()),
			'name'=>ucfirst($user->patient->firstName).' '.ucfirst($user->patient->lastName),
			'username' => $user->email,
			'email' => $user->email,
			'nickname' => $user->patient->nickName,
			'gender' => @$user->patient->gender->name,
			'age'=>@$user->getAgeAttribute($user->patient->dob),
			'dateOfBirth' => date("m/d/Y",strtotime($user->patient->dob)),
			'height' => @$user->patient->height,
			'contactNo' => @$user->patient->phoneNumber,
			'phoneNumber' => @$user->patient->phoneNumber,
			'isDeviceAdded' => @$user->patient->isDeviceAdded,
			'house_no' => @$user->patient->appartment,
			'profile_photo' => (!empty($user->profilePhoto))&&(!is_null($user->profilePhoto)) ? str_replace("public", "", URL::to('/')).'/'.$user->profilePhoto : "",
			'city' => @$user->patient->city,
			'state' => @$user->patient->state->name,
			'country' => @$user->patient->country->name,
			'zipCode' => $user->patient->zipCode,
			'deviceType'=>$user->deviceType,
			'isDeviceAdded'=>$user->patient->isDeviceAdded,
			'deviceToken'=>$user->deviceToken,
			'roleId' => $this->showData ? fractal()->item($user->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'vital'=>(!empty($user->userFamilyAuthorization)) ? $user->userFamilyAuthorization->vital==0 ? 0 :$user->userFamilyAuthorization->vital : '',
		    'message'=>(!empty($user->userFamilyAuthorization))? $user->userFamilyAuthorization->message==0 ? 0 :$user->userFamilyAuthorization->message : '',
			'emailverified' =>$user->emailVerify ? true : false,
			'patient'=> $user->patient ? fractal()->item($user->patient)->transformWith(new PatientTransformer(false))->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'staff'=>$user->staff ? fractal()->item($user->staff)->transformWith(new StaffTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'famailyMember'=>$user->familyMember ? fractal()->item($user->familyMember)->transformWith(new PatientFamilyMemberTransformer(false))->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			
		];
	}
}
