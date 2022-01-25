<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientCountTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data)
	{ 
		return [
			'data' => $data,
		];
	}


}