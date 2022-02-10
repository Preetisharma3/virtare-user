<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PatientProgramTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->patientProgramUdid,
            'program'=>$data->name,
            'onboardingScheduleDate'=>$data->onboardingScheduleDate,
            'dischargeDate'=>$data->dischargeDate,
            'status'=>$data->isActive==1?'Active':'Inactive',
		];
	}
}
