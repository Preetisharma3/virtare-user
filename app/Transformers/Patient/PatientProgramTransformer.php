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
			'id'=>$data->id,
            'program'=>$data->program->type->name,
            'patientId'=>$data->patientId,
            'onboardingScheduleDate'=>strtotime($data->onboardingScheduleDate),
            'dischargeDate'=>strtotime($data->dischargeDate),
            'status'=>$data->isActive==1?'Active':'Inactive',
		];
	}
}
