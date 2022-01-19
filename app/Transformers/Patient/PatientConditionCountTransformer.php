<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientConditionCountTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
            'text'=>$data->condition->name,
            'count'=>$data['count'],
		];
	}


}