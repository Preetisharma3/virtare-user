<?php

namespace App\Transformers\Conversation;

use League\Fractal\TransformerAbstract;


class ConversationTransformer extends TransformerAbstract
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
            'conversationId' => $data->conversationId,
            'senderId' => $data->senderId,
            'message' => $data->message,
            'type' => $data->type,
            'isRead' => $data->isRead,
            "createdAt"=>$data->createdAt,
        ];
    }
}