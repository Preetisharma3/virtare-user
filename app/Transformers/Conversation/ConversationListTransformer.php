<?php

namespace App\Transformers\Conversation;

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
        return [
            'id' => $data->id,
            'sender' => $data->sender->patient ? $data->sender->patient->firstName . ' ' . $data->sender->patient->lastName : $data->sender->staff->firstName . ' ' . $data->sender->staff->lastName,
            'receiver' => $data->receiver->patient ? $data->receiver->patient->firstName . ' ' . $data->receiver->patient->lastName : $data->receiver->staff->firstName . ' ' . $data->receiver->staff->lastName,
            'message' => (!empty($data->conversationMessages->last()->message)) ? $data->conversationMessages->last()->message : '',
            'type' => (!empty($data->conversationMessages->last()->type)) ? $data->conversationMessages->last()->type : '',
            'isRead' => (!empty($data->conversationMessages->last()->isRead)) ? 1 : 0,
            "createdAt" => (!empty($data->conversationMessages->last()->createdAt)) ? strtotime($data->conversationMessages->last()->createdAt) : '',
        ];
    }
}
