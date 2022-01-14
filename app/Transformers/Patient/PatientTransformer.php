<?php

namespace App\Transformers\Patient;

use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;
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
           // 'otherLanguage' => $data->otherLanguage->name,
            'nickName' => $data->nickName,
            'height' => $data->height,
            'weight' => $data->weight,
            'phoneNumber' => $data->phoneNumber,
            //'contactType' => $data->contactType->name,
            'contactTime' => $data->contactTime->name,
            'country' => $data->country->name,
            'state' => $data->state->name,
            'city' => $data->city,
            'zipCode' => $data->zipCode,
            'appartment' => $data->weight,
            'address' => $data->address,
            'email' => $data->user->email,
            'patientFamilyMember' => fractal()->item($data->family)->transformWith(new PatientFamilyMemberTransformer())->toArray(),
            'emergencyContact' => fractal()->item($data->emergency)->transformWith(new PatientFamilyMemberTransformer())->toArray(),
        ];
    }
}
