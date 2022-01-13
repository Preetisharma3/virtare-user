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
            'program'=>$data->globalCode->name,
            'patientId'=>$data->patientId,
            'onboardingScheduleDate'=>$data->onboardingScheduleDate,
            'dischargeDate'=>$data->dischargeDate,
            'status'=>$data->isActive,
		];
	}
}
