<?php

namespace App\Transformers\User;

use App\Models\User\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform(User $user): array
	{
		return [
			'uuid' => $user->udid,
			'email' => $user->email,
			'roleId' => $user->roleId,
			'emailVerify' =>$user->emailVerify,
		];
	}
}
