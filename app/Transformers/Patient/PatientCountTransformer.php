<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientCountTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
            'text'=>$data['text'],
            'count'=>$data['count'],
		];
	}


}