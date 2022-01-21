<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;
use App\Transformers\Patient\PatientFlagTransformer;
use App\Transformers\Patient\PatientVitalFieldTransformer;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class PatientTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        return [
            'id' => $data->id,
            'firstName' => $data->firstName,
            'middleName' => $data->middleName,
            'lastName' => $data->lastName,
            'dob' => $data->dob,
            'gender' => $data->gender->name,
            'language' => $data->language->name,
            'otherLanguage' => $data->otherLanguageId,
            'nickName' => $data->nickName,
            'height' => $data->height,
            'weight' => $data->weight,
            'phoneNumber' => $data->phoneNumber,
           'contactType' => $data->contactTypeId,
           'contactTime' => $data->contactTimeId,
            'country' => $data->country->name,
           'state' => $data->state->name,
            'city' => $data->city,
            'zipCode' => $data->zipCode,
            'appartment' => $data->weight,
            'address' => $data->address,
            'email' => $data->user->email,
            'isActive' => $data->isActive,
            'nonCompliance'=>'N/A',
            'lastReadingDate'=>'N/A',
            'lastMessageSent'=>'N/A',
            'vitalType'=>$data->vitalTypeId,
            'vitalField'=>$data->name,
            'flagName'=>'jhj',
            'flagColor'=>'fhghg',
            'patientFamilyMember' => fractal()->item($data->family)->transformWith(new PatientFamilyMemberTransformer())->toArray(),
            'emergencyContact' => fractal()->item($data->emergency)->transformWith(new PatientFamilyMemberTransformer())->toArray(),
           // 'patientVitals' => fractal()->collection($data->vitals)->transformWith(new PatientVitalTransformer())->toArray(),
            'patientFlags' => fractal()->collection($data->flags)->transformWith(new PatientFlagTransformer())->toArray(),
        ];
    }
}
