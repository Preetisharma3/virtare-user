<?php

namespace App\Transformers\Patient;

use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientFamilyMemberTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];
	public function __construct($showData = true)
    {
        $this->showData = $showData;
    }
	public function transform($data): array
	{
			return [
				'id' => $data->id,
				'sipId' => "UR".$data->userId,
				'udid' => $data->udid,
				'id' => $data->udid,
				'patientId'=>(!empty($data->patientId))?$data->patientId:'',
				'fullName' =>(!empty($data->fullName))?ucfirst($data->fullName):'',
				'gender' => (!empty($data->gender->name))?$data->gender->name:'',
				'genderId' => (!empty($data->gender->id))?$data->gender->id:'',
				'phoneNumber' => (!empty($data->phoneNumber))?$data->phoneNumber:'',
				'contactType' => (!empty($data->contactTypeId))?$data->contactTypeId:[],
				'contactTime' => (!empty($data->contactTime->name))?$data->contactTime->name:[],
				'contactTimeId' => (!empty($data->contactTimeId))?$data->contactTimeId:[],
				'relation' => (!empty($data->relation->name))?$data->relation->name:'',
				'relationId' => (!empty($data->relationId))?$data->relationId:'',
				'isPrimary'=>(!empty($data->isPrimary))?$data->isPrimary:'',
				'vital'=>(!empty($data->vital))?$data->vital:0,
				'message'=>(!empty($data->messages))?$data->messages:0,
				'email'=>(!empty($data->user->email))?$data->user->email:'',
				'user' =>($data->user) && ($this->showData)? fractal()->item($data->user)->transformWith(new UserTransformer())->toArray():[],
			];
	}
}
