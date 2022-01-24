<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\Flag\FlagTransformer;

class PatientFlagTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		return [
            'id'=>$data->id,
            'patientId'=>$data->patientId,
            'flags'=> fractal()->item($data->flag)->transformWith(new FlagTransformer())->toArray()
		];
	}


}