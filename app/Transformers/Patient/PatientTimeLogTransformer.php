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
            'category'=>(!empty($data->categoryName))?$data->categoryName:$data->category->name,
            'loggedId'=>$data->loggedId,
            'loggedBy'=>@$data->logged->firstName.' '.@$data->logged->lastName,
            'performedId'=>$data->performedId,
            'performedBy'=>(!empty($data->performedBy))?$data->performedBy:@$data->performed->firstName,
            'date'=>strtotime($data->date),
            'timeAmount'=>$data->timeAmount,
            'patient'=>(!empty($data->patientName))?$data->patientname:@$data->patient->firstName.' '.@$data->patient->middleName.' '.@$data->patient->lastName,
            'patientId'=>$data->patient->udid,
            'staff'=>@$data->performed->firstName.' '.@$data->performed->lastName,
            'staffId'=>@$data->performed->udid,
            'flag'=>'#FFB21E',
            'note'=>(!empty($data->notes->note))?$data->notes->note:'',
            'noteId'=>(!empty($data->notes->id))?$data->notes->id:'',
            'cptCodeId'=>(!empty($data->cptCodeId))?$data->cptCodeId:'',
            'cptCode'=>(!empty($data->cptCode->name))?$data->cptCode->name:''
        ];
    }
}
