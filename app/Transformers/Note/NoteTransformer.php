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
            'id' => $data->udid,
            'date' => strtotime($data->date),
            'category' => (!empty($data->categoryName->name))?$data->categoryName->name:$data->category,
            'type' => $data->type,
            'note' => $data->note,
            'addedBy'=>(!empty(@$data->firstName))?ucfirst(@$data->firstName).' '.ucfirst(@$data->lastName):(!empty($data->user->staff->firstName))?ucfirst(@$data->user->staff->firstName).' '.ucfirst(@$data->user->staff->lastName):ucfirst(@$data->user->patient->firstName).' '.ucfirst(@$data->user->patient->lastName),
            'addedByDetail'=>@$data->user,
            'flag'=>'#39B5C2'
        ];
    }
}
