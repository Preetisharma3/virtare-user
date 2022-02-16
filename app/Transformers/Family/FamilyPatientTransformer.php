<?php

namespace App\Transformers\Family;

use League\Fractal\TransformerAbstract;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class FamilyPatientTransformer extends TransformerAbstract
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
           'id'=>$data->patientId,
           'name'=> ucfirst($data->patients->firstName).' '.ucfirst($data->patients->lastName),
           'dob'=>$data->patients->dob,
           'gender'=>$data->patients->gender->name,
           'language'=>$data->patients->language->name,
           'phoneNumber'=>$data->patients->phoneNumber,
           'medicalRecordNumber'=>$data->patients->medicalRecordNumber,
           'country'=>$data->patients->country->name,
           'state'=>$data->patients->state->name,
           'city'=>$data->patients->city,
           'zipCode'=>$data->patients->zipCode,
           'appartment'=>$data->patients->appartment,
           'address'=>$data->patients->address,
           'isDeviceAdded'=>$data->patients->isDeviceAdded,
           'isActive'=>$data->patients->isActive        ,
		];
    }
}
