<?php

namespace App\Transformers\Patient;

use Illuminate\Support\Facades\URL;
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
            'udid'=>$data->udid,
            'name' => ucfirst($data->firstName),
            'middleName' => ucfirst($data->middleName),
            'lastName' => ucfirst($data->lastName),
            'dob' => $data->dob,
            'gender' => $data->gender->name,
            'language' => $data->language->name,
            'otherLanguage' => $data->otherLanguageId,
            'nickName' => ucfirst($data->nickName),
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
            'isActive' => $data->isActive == 1 ? 'Active' : 'Inactive',
            'nonCompliance' => 'N/A',
            'lastReadingDate' => 'N/A',
            'lastMessageSent' => 'N/A',
            'vitalType' => $data->vitalTypeId,
            'vitalField' => $data->name,
            'flagName' => 'jhj',
            'flagColor' => 'fhghg',
            'medicalRecordNumber'=>$data->medicalRecordNumber,
            'profile_photo'=>(!empty($data->user->profilePhoto))&&(!is_null($data->user->profilePhoto)) ? str_replace("public","",URL::to('/')).'/'.$data->user->profilePhoto : "",
            'patientFamilyMember' => fractal()->item($data->family)->transformWith(new PatientFamilyMemberTransformer())->toArray(),
            'emergencyContact' => fractal()->item($data->emergency)->transformWith(new PatientFamilyMemberTransformer())->toArray(),
            'patientFlags' => $data->flags ? fractal()->collection($data->flags)->transformWith(new PatientFlagTransformer())->toArray() : [],
            'patientVitals'=> $data->vitals ?fractal()->collection($data->vitals)->transformWith(new PatientVitalTransformer())->toArray(): [],

        ];
    }
}
