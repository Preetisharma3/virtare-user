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
            'categoryId'=>$data->categoryId,
            'category'=>$data->category->name,
            'loggedId'=>$data->loggedId,
            'loggedBy'=>$data->logged->firstName,
            'performedId'=>$data->performedId,
            'performedBy'=>$data->performed->firstName,
            'date'=>strtotime($data->date),
            'timeAmount'=>strtotime($data->timeAmount),
		];
	}
}
