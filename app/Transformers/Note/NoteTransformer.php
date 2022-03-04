<?php

namespace App\Transformers\Note;

use App\Transformers\Screen\ScreenTransformer;
use League\Fractal\TransformerAbstract;


class NoteTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        //
    ];


    protected $availableIncludes = [
        //
    ];


    public function transform($data): array
    {
        if(empty($data)){
            return [];
        }
        return [
            'id' => $data->id,
            'date' => strtotime($data->date),
            'category' => $data->category,
            'type' => $data->type,
            'note' => $data->note,
            'addedBy'=>ucfirst(@$data->firstName).' '.ucfirst(@$data->lastName),
            'flag'=>'#39B5C2'
        ];
    }
}
