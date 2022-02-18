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
            'id' => $data->udid,
            'udid'=>$data->udid,
            'firstName' => ucfirst($data->firstName),
            'name' => ucfirst($data->firstName),
            'middleName' => (!empty($data->middleName))?ucfirst($data->middleName):'',
            'lastName' => ucfirst($data->lastName),
            'patientFullName'=>ucfirst($data->firstName).' '.ucfirst($data->middleName).' '.ucfirst($data->lastName),
            'fullName'=>ucfirst($data->firstName).' '.ucfirst($data->middleName).' '.ucfirst($data->lastName),
            'dob' => $data->dob,
            'gender' => (!empty($data->gender->name))?$data->gender->name:'',
            'genderId' => (!empty($data->genderId))?$data->genderId:'',
            'language' => (!empty($data->language->name))?$data->language->name:'',
            'languageId' => (!empty($data->languageId))?$data->languageId:'',
            'otherLanguage' =>(!empty($data->otherLanguageId))? $data->otherLanguageId:'',
            'nickName' => (!empty($data->nickName))?ucfirst($data->nickName):'',
            'height' =>(!empty($data->height))?$data->height:'',
            'weight' => (!empty($data->weight))?$data->weight:'',
            'phoneNumber' => $data->phoneNumber,
            'contactType' => (!empty($data->contactTypeId))?$data->contactTypeId:'',
            'contactTime' => (!empty($data->contactTime->name))?$data->contactTime->name:'',
            'contactTimeId' => (!empty($data->contactTimeId))?$data->contactTimeId:'',
            'country' => (!empty($data->country->name))?$data->country->name:'',
            'countryId' => (!empty($data->countryId))?$data->countryId:'',
            'state' => (!empty($data->state->name))?$data->state->name:'',
            'stateId' => (!empty($data->stateId))?$data->stateId:'',
            'city' => (!empty($data->city))?$data->city:'',
            'zipCode' => (!empty($data->zipCode))?$data->zipCode:'',
            'appartment' => (!empty($data->appartment))?$data->appartment:'',
            'address' => (!empty($data->address))?$data->address:'',
            'email' => $data->user->email,
            'isActive' => $data->isActive == 1 ? 'Active' : 'Inactive',
            'nonCompliance' => 'N/A',
            'lastReadingDate' => 'N/A',
            'lastMessageSent' => 'N/A',
            'flagName' => 'jhj',
            'flagColor' => 'fhghg',
            'medicalRecordNumber'=>(!empty($data->medicalRecordNumber))?$data->medicalRecordNumber:'',
            'profile_photo'=>(!empty($data->user->profilePhoto))&&(!is_null($data->user->profilePhoto)) ? str_replace("public","",URL::to('/')).'/'.$data->user->profilePhoto : "",
            'patientFamilyMember' =>$data->family? fractal()->item($data->family)->transformWith(new PatientFamilyMemberTransformer())->toArray():[],
            'emergencyContact' => $data->emergency?fractal()->item($data->emergency)->transformWith(new PatientFamilyMemberTransformer())->toArray():[],
            'patientFlags' => $data->flags ? fractal()->collection($data->flags)->transformWith(new PatientFlagTransformer())->toArray() : [],
            'patientVitals'=> $data->vitals ?fractal()->collection($data->vital)->transformWith(new PatientVitalTransformer())->toArray(): [],

        ];
    }
}
