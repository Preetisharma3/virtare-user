<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;


class PatientCriticalNoteTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data): array
    {
        return [
            'id' => $data->id,
            'udid' => $data->udid,
            'patientId' => $data->patientId,
            'criticalNote' => $data->criticalNote,
            'isRead' =>$data->isRead,
            
		];
    }
}
