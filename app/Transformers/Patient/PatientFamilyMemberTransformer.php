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
				'fullName' =>ucfirst($data->fullName) ,
				'gender' => $data->gender->name,
				'phoneNumber' => $data->phoneNumber,
				'contactType' => $data->contactTypeId,
				'contactTime' => $data->contactTimeId,
				'relation' => $data->relation->name,
				'email' => $data->user->email,
			];
		}
		else{
			return [
				'id' => $data->id,
				'patientId'=>$data->patientId,
				'fullName' => ucfirst($data->fullName),
				'gender' => $data->gender->name,
				'phoneNumber' => $data->phoneNumber,
				'contactType' => $data->contactTypeId,
				'contactTime' => $data->contactTimeId,
				'email' => $data->email,
			];
		}
		
	}
}
