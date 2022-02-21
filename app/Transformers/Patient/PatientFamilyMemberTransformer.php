<?php

namespace App\Transformers\Patient;

use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientFamilyMemberTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
			return [
				'id' => $data->udid,
				'patientId'=>(!empty($data->patientId))?$data->patientId:'',
				'fullName' =>(!empty($data->fullName))?ucfirst($data->fullName):'',
				'gender' => (!empty($data->gender->name))?$data->gender->name:'',
				'phoneNumber' => (!empty($data->phoneNumber))?$data->phoneNumber:'',
				'contactType' => (!empty($data->contactTypeId))?$data->contactTypeId:'',
				'contactTime' => (!empty($data->contactTime->name))?$data->contactTime->name:'',
				'contactTimeId' => (!empty($data->contactTimeId))?$data->contactTimeId:'',
				'relation' => (!empty($data->relation->name))?$data->relation->name:'',
				'relationId' => (!empty($data->relationId))?$data->relationId:'',
				'isPrimary'=>(!empty($data->isPrimary))?$data->isPrimary:'',
				'user' =>$data->user? fractal()->item($data->user)->transformWith(new UserTransformer())->toArray():[],
			];
	}
}
