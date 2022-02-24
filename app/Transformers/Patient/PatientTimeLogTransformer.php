<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;

class PatientTimeLogTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data): array
	{
		if(empty($data)){
            return [];
        }
		return [
			'id'=>$data->udid,
            'categoryId'=>$data->categoryId,
            'category'=>$data->category,
            'loggedId'=>$data->loggedId,
            'loggedBy'=>$data->logged->firstName,
            'performedId'=>$data->performedId,
            'performedBy'=>$data->performed->firstName,
            'date'=>strtotime($data->date),
            'timeAmount'=>strtotime($data->timeAmount),
            'patient'=>$data->patient->firstName.' '.$data->patient->middleName.' '.$data->patient->lastName,
            'patientId'=>$data->patient->id,
            'staff'=>$data->performed->firstName,
            'staffId'=>$data->performed->udid,
            'flag'=>'#FFB21E',
            'note'=>(!empty($data->notes->note))?$data->notes->note:'',
            'noteId'=>(!empty($data->notes->id))?$data->notes->id:''
		];
	}
}
