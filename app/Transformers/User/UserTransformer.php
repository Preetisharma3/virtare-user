<?php

namespace App\Transformers\User;

use App\Models\User\User;
use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;

class UserTransformer extends TransformerAbstract
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
			'id' => $user->id,
			'udid' => $user->udid,
			'staffUdid' => @$user->staff ? $user->staff->udid : @$user->familyMember->udid,
			'sipId' => "UR" . $user->id,
			'roleId' => $this->showData ? fractal()->item($user->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'name' => @$user->staff ? @$user->staff->firstName . ' ' . @$user->staff->lastName : @$user->familyMember->fullName,
			'username' => $user->email,
			'email' => $user->email,
			'profile_photo' => (!empty($user->profilePhoto)) && (!is_null($user->profilePhoto)) ? str_replace("public", "", URL::to('/')) . '/' . $user->profilePhoto : "",
			'emailverified' => $user->emailVerify ? true : false,
			'contactType' => @$user->familyMember->contactType->name ?  @$user->familyMember->contactType->name : '',
			'contactTime' => @$user->familyMember->contactTime->name ?  @$user->familyMember->contactTime->name : '',
			'gender' => @$user->staff->gender->name ? @$user->staff->gender->name : @$user->familyMember->gender->name,
			'network' => @$user->staff->network->name ? @$user->staff->network->name : '',
			'specialization' => @$user->staff->specialization->name ? @$user->staff->specialization->name : '',
			'designation' => @$user->staff->designation->name ? @$user->staff->designation->name : '',
			'contact_no' => @$user->staff->phoneNumber ? @$user->staff->phoneNumber : @$user->familyMember->phoneNumber,
			'relation' => @$user->familyMember->relation->name ?  @$user->familyMember->relation->name : '',
			'deviceType' => $user->deviceType,
			'deviceToken' => $user->deviceToken,

		];
	}
}
