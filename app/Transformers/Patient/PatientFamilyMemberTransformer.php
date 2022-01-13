<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientFamilyMemberTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id' => $data->id,
			'fullName' => $data->fullName,
			'gender' => $data->gender->name,
			'phoneNumber' => $data->phoneNumber,
			'contactType' => $data->contactType->name,
			'contactTime' => $data->contactTime->name,
			//'relation' => $data->relation->name,
			'email' => $data->user->email,
		];
	}
}
