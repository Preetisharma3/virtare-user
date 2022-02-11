<?php

namespace App\Transformers\Patient;

use Illuminate\Support\Facades\URL;
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
			'icon'=>(!empty($data->icon))&&(!is_null($data->icon)) ? URL::to('/').'/'.$data->icon : "",
            'flags'=> fractal()->item($data->flag)->transformWith(new FlagTransformer())->toArray(),
		];
	}


}