<?php

namespace App\Transformers\Patient;

use App\Helper;
use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;
use App\Transformers\Patient\PatientFlagTransformer;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class PatientTransformer extends TransformerAbstract
{
    protected $showData;

    public function __construct($showData = true)
    {
        $this->showData = $showData;
    }
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    public function transform($data): array
    {
        if (!$data->family->relationId || !$data->genderId) {
            $relation = '';
        } else {
            $relation = Helper::relation($data->family->relationId, $data->genderId);
        }
        return [
            'id' => $data->udid,
            'sipId' => "UR" . $data->user->id,
            'firstName' => ucfirst($data->firstName),
            'name' => ucfirst($data->firstName),
            'middleName' => (!empty($data->middleName)) ? ucfirst($data->middleName) : '',
            'lastName' => ucfirst($data->lastName),
            'patientFullName' => ucfirst($data->firstName) . ' ' . ucfirst($data->middleName) . ' ' . ucfirst($data->lastName),
            'fullName' => ucfirst($data->firstName) . ' ' . ucfirst($data->middleName) . ' ' . ucfirst($data->lastName),
            'dob' => $data->dob,
            'gender' => (!empty($data->gender->name)) ? $data->gender->name : '',
            'genderId' => (!empty($data->genderId)) ? $data->genderId : '',
            'language' => (!empty($data->language->name)) ? $data->language->name : '',
            'languageId' => (!empty($data->languageId)) ? $data->languageId : '',
            'otherLanguage' => (!empty($data->otherLanguageId)) ? $data->otherLanguageId : '',
            'nickName' => (!empty($data->nickName)) ? ucfirst($data->nickName) : '',
            'height' => (!empty($data->height)) ? $data->height : '',
            'weight' => (!empty($data->weight)) ? $data->weight : '',
            'phoneNumber' => $data->phoneNumber,
            'contactType' => (!empty($data->contactTypeId)) ? $data->contactTypeId : '',
            'contactTime' => (!empty($data->contactTime->name)) ? $data->contactTime->name : '',
            'contactTimeId' => (!empty($data->contactTimeId)) ? $data->contactTimeId : '',
            'country' => (!empty($data->country->name)) ? $data->country->name : '',
            'countryId' => (!empty($data->countryId)) ? $data->countryId : '',
            'state' => (!empty($data->state->name)) ? $data->state->name : '',
            'stateId' => (!empty($data->stateId)) ? $data->stateId : '',
            'city' => (!empty($data->city)) ? $data->city : '',
            'zipCode' => (!empty($data->zipCode)) ? $data->zipCode : '',
            'appartment' => (!empty($data->appartment)) ? $data->appartment : '',
            'address' => (!empty($data->address)) ? $data->address : '',
            'isActive' => $data->isActive == 1 ? 'Active' : 'Inactive',
            'nonCompliance' => 'N/A',
            'lastReadingDate' => 'N/A',
            'lastMessageSent' => 'N/A',
            'flagName' => 'jhj',
            'flagColor' => 'fhghg',
            'relationId' => (!empty($relation['relationId'])) ? $relation['relationId']:'',
            'relation' => (!empty($relation['relation'])) ? $relation['relation']:'',
            'user' => $this->showData && $data->user ? fractal()->item($data->user)->transformWith(new UserTransformer(false))->toArray() : [],
            'medicalRecordNumber' => (!empty($data->medicalRecordNumber)) ? $data->medicalRecordNumber : '',
            'profile_photo' => (!empty($data->user->profilePhoto)) && (!is_null($data->user->profilePhoto)) ? str_replace("public", "", URL::to('/')) . '/' . $data->user->profilePhoto : "",
            'profilePhoto' => (!empty($data->user->profilePhoto)) && (!is_null($data->user->profilePhoto)) ? str_replace("public", "", URL::to('/')) . '/' . $data->user->profilePhoto : "",
            'patientFamilyMember' => $this->showData && $data->family ? fractal()->item($data->family)->transformWith(new PatientFamilyMemberTransformer())->toArray() : [],
            'emergencyContact' => $this->showData && $data->emergency ? fractal()->item($data->emergency)->transformWith(new PatientFamilyMemberTransformer())->toArray() : [],
            'patientFlags' => $this->showData && $data->flags ? fractal()->collection($data->flags)->transformWith(new PatientFlagTransformer())->toArray() : [],
            'patientVitals' => $this->showData && $data->vitals ? fractal()->collection($data->vitals)->transformWith(new PatientVitalTransformer())->toArray() : [],
        ];
    }
}
