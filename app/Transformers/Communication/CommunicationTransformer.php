<?php

namespace App\Transformers\Communication;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;
use Illuminate\Support\Facades\Auth;

class CommunicationTransformer extends TransformerAbstract
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
        $dataArray =  [
             'id'=>$data->id,
            'from'=>$data->sender->patient ? @$data->sender->patient->firstName . ' ' . @$data->sender->patient->lastName : @$data->sender->staff->firstName . ' ' . @$data->sender->staff->lastName,
            'type'=>$data->type->name,
            'to'=>$data->receiver->patient ? @$data->receiver->patient->firstName . ' ' . @$data->receiver->patient->lastName : @$data->receiver->staff->firstName . ' ' . @$data->receiver->staff->lastName,
            'category'=>$data->globalCode->name,
            'priority'=>$data->priority->name,
            'createdAt'=>strtotime($data->createdAt),
            'senderId' => $data->from,
            'sender' => @$data->sender->patient ? @$data->sender->patient->firstName . ' ' . @$data->sender->patient->lastName : @$data->sender->staff->firstName . ' ' . @$data->sender->staff->lastName,
            'receiverId' => $data->referenceId,
            'profile_photo' => (!empty($data->receiver->profilePhoto)) && (!is_null($data->receiver->profilePhoto)) ? URL::to('/') . '/' . $data->receiver->profilePhoto : "",
            'profilePhoto' => (!empty($data->receiver->profilePhoto)) && (!is_null($data->receiver->profilePhoto)) ? URL::to('/') . '/' . $data->receiver->profilePhoto : "",
            'expertise' => (!empty($data->receiver->staff->expertise)) ? $data->receiver->staff->expertise->name : '',
            'designation' => $data->receiver->staff ? $data->receiver->staff->designation->name : '',
            'specialization' => $data->receiver->staff ? $data->receiver->staff->specialization->name : '',
            'receiver' => $data->receiver->patient ? @$data->receiver->patient->firstName . ' ' . @$data->receiver->patient->lastName : @$data->receiver->staff->firstName . ' ' . @$data->receiver->staff->lastName,
            'message' => (!empty($data->conversationMessages->last()->message)) ? $data->conversationMessages->last()->message : '',
            'messageType' => (!empty($data->conversationMessages->last()->type)) ? $data->conversationMessages->last()->type : '',
            'messageSender' => (!empty($data->conversationMessages->last()->senderId)) ? $data->conversationMessages->last()->senderId : '',
            'isRead' => (!isset($data->conversationMessages)) && ($data->conversationMessages->last()->isRead==0) ? 0 : 1,
            "created_at" => (!empty($data->conversationMessages->last()->createdAt)) ? strtotime($data->conversationMessages->last()->createdAt) : '',
        ];


        if(@$data->sender->patient){
            if((!empty($data->sender['profilePhoto'])) && (!is_null($data->sender['profilePhoto']))){
                $dataArray['patientPic'] = str_replace("public", "", URL::to('/')) . '/' . $data->sender['profilePhoto'];
            }else{
                $dataArray['patientPic'] = '';
            }
            $dataArray['patientName'] = @$data->sender->patient->firstName . ' ' . @$data->sender->patient->lastName ;
        }elseif($data->receiver->patient){
            if((!empty($data->receiver['profilePhoto'])) && (!is_null($data->receiver['profilePhoto']))){
                $dataArray['patientPic'] = str_replace("public", "", URL::to('/')) . '/' . $data->receiver['profilePhoto'];
            }else{
                $dataArray['patientPic'] = '';
                
            }
            $dataArray['patientName'] = @$data->receiver->patient->firstName . ' ' . @$data->receiver->patient->lastName ;
        }elseif(auth()->user()->Id == $data->sender['Id']){
            $dataArray['patientPic'] = str_replace("public", "", URL::to('/')) . '/' . $data->receiver['profilePhoto'];
            $dataArray['patientName'] = @$data->receiver->staff->firstName . ' ' . @$data->receiver->staff->lastName;
        }else{
            $dataArray['patientPic'] = str_replace("public", "", URL::to('/')) . '/' . $data->sender['profilePhoto'];
            $dataArray['patientName'] = @$data->sender->staff->firstName . ' ' . @$data->sender->staff->lastName;
        }
        return $dataArray;
    }
}
