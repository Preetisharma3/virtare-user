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
		if($data->relation){
			return [
				'id' => $data->id,
				'patientId'=>(!empty($data->patientId))?$data->patientId:'',
				'fullName' =>(!empty($data->fullName))?ucfirst($data->fullName):'',
				'gender' => (!empty($data->gender->name))?$data->gender->name:'',
				'phoneNumber' => (!empty($data->phoneNumber))?$data->phoneNumber:'',
				'contactType' => (!empty($data->contactTypeId))?$data->contactTypeId:'',
				'contactTime' => (!empty($data->contactTimeId))?$data->contactTimeId:'',
				'relation' => (!empty($data->relation->name))?$data->relation->name:'',
				'email' => (!empty($data->user->email))?$data->user->email:'',
				'isPrimary'=>(!empty($data->isPrimary))?$data->isPrimary:'',
				'profile_photo'=>(!empty($data->user->profilePhoto))&&(!is_null($data->user->profilePhoto)) ? URL::to('/').'/'.$data->user->profilePhoto : "",
			];
		}
		else{
			return [
				'id' => $data->id,
				'patientId'=>(!empty($data->patientId))?$data->patientId:'',
				'fullName' => (!empty($data->fullName))?ucfirst($data->fullName):'',
				'gender' => (!empty($data->gender->name))?$data->gender->name:'',
				'phoneNumber' => (!empty($data->phoneNumber))?$data->phoneNumber:'',
				'contactType' => (!empty($data->contactTypeId))?$data->contactTypeId:'',
				'contactTime' => (!empty($data->contactTimeId))?$data->contactTimeId:'',
				'email' => (!empty($data->email))?$data->email:'',
				'profile_photo'=>(!empty($data->user->profilePhoto))&&(!is_null($data->user->profilePhoto)) ? URL::to('/').'/'.$data->user->profilePhoto : "",
			];
		}
		
	}
}
