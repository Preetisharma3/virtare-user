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
            'year' => $data['year'],
            'data' => $data['data'],
        ];
    }
}
