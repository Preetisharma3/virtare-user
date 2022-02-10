<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientTimeLineTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
			'id'=>$data->udid,
            'heading'=>$data->heading,
            'title'=>$data->title,
            'type'=>$data->type,
		];
	}
}
