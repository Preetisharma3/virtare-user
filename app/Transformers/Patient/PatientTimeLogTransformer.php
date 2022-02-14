<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientTimeLogTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->udid,
            'category'=>$data->category->name,
            'loggedBy'=>$data->logged->firstName,
            'performedBy'=>$data->performed->firstName,
            'date'=>strtotime($data->date),
            'timeAmount'=>strtotime($data->timeAmount),
		];
	}
}
