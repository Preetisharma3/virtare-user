<?php

namespace App\Transformers\Conversation;

use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;


class ConversationListTransformer extends TransformerAbstract
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
        // dd($data->sender);
        return [
            'id' => $data->id,
            'senderId'=>$data->from,
            'sender' => $data->sender->patient ? $data->sender->patient->firstName . ' ' . $data->sender->patient->lastName : $data->sender->staff->firstName . ' ' . $data->sender->staff->lastName,
            'receiverId'=>$data->referenceId,
            'profile_photo'=>(!empty($data->receiver->profilePhoto))&&(!is_null($data->receiver->profilePhoto)) ? URL::to('/').'/'.$data->receiver->profilePhoto : "",
            'profilePhoto'=>(!empty($data->receiver->profilePhoto))&&(!is_null($data->receiver->profilePhoto)) ? URL::to('/').'/'.$data->receiver->profilePhoto : "",
            'expertise' => (!empty($data->receiver->staff->expertise)) ? $data->receiver->staff->expertise->name : '',
            'designation' => $data->receiver->staff ? $data->receiver->staff->designation->name :'',
            'specialization' => $data->receiver->staff ? $data->receiver->staff->specialization->name : '',
            'receiver' => $data->receiver->patient ? $data->receiver->patient->firstName . ' ' . $data->receiver->patient->lastName : $data->receiver->staff->firstName . ' ' . $data->receiver->staff->lastName,
            'message' => (!empty($data->conversationMessages->last()->message)) ? $data->conversationMessages->last()->message : '',
            'type' => (!empty($data->conversationMessages->last()->type)) ? $data->conversationMessages->last()->type : '',
            'messageSender' => (!empty($data->conversationMessages->last()->senderId)) ? $data->conversationMessages->last()->senderId : '',
            'isRead' => (!empty($data->conversationMessages->last()->isRead)) ? 1 : 0,
            "createdAt" => (!empty($data->conversationMessages->last()->createdAt)) ? strtotime($data->conversationMessages->last()->createdAt) : '',
        ];
    }
}
