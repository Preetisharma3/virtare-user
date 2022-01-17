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
		if($data->relation){
			return [
				'id' => $data->id,
				'patientId'=>$data->patientId,
				'fullName' => $data->fullName,
				'gender' => $data->gender->name,
				'phoneNumber' => $data->phoneNumber,
				'contactType' => $data->contactTypeId,
				'contactTime' => $data->contactTime->name,
				'relation' => $data->relation->name,
				'email' => $data->user->email,
			];
		}
		else{
			return [
				'id' => $data->id,
				'patientId'=>$data->patientId,
				'fullName' => $data->fullName,
				'gender' => $data->gender->name,
				'phoneNumber' => $data->phoneNumber,
				'contactType' => $data->contactTypeId,
				'contactTime' => $data->contactTime->name,
				'email' => $data->email,
			];
		}
		
	}
}
