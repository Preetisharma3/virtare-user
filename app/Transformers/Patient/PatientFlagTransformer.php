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
            'id'=>(!empty($data->udid))?$data->udid:$data->patientFlagId,
            'patientId'=>(!empty($data->patientId))?$data->patientId:$data->patientFlagPatientId,
			'icon'=>(!empty($data->icon))&&(!is_null($data->icon)) ? URL::to('/').'/'.$data->icon : "",
            'flags'=> (!empty($data->flag))?fractal()->item($data->flag)->transformWith(new FlagTransformer())->toArray():'',
			'flagName'=>(!empty($data->flagName))?$data->flagName:',',
			'flagColor'=>(!empty($data->flagColor))?$data->flagColor:',',
		];
	}


}