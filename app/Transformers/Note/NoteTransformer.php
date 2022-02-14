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
        return [
            'id' => $data->udid,
            'date' => strtotime($data->date),
            'category' => $data->category->name,
            'type' => $data->typeName->name,
            'note' => $data->note,
            'addedBy'=>$data->user->staff->firstName,
            'flag'=>'#39B5C2'
        ];
    }
}