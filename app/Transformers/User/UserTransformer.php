<?php

namespace App\Transformers\User;

use App\Models\User\User;
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
		dd($user);
		return [
			'id'=>$user->id,
			'uuid' => $user->udid,
			'roleId' => $this->showData ? fractal()->item($user->roles)->transformWith(new RoleTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray() : new \stdClass(),
			'name'=>$user->staff->firstName.' '.$user->staff->lastName,
			'username'=>$user->email,
			'email'=>$user->email,
			'emailverified' =>$user->emailVerify ? true : false,
			'gender'=>$user->staff->gender->name,
			'network'=>$user->staff->network->name,
			'specialization'=>$user->staff->specialization->name,
			'designation'=>$user->staff->designation->name,
			'contact_no'=>$user->staff->phoneNumber,
		];
	}
}
