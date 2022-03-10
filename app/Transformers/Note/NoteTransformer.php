<?php

namespace App\Transformers\Note;

use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;


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
            'addedBy'=>(!empty(@$data->firstName))?ucfirst(@$data->firstName).' '.ucfirst(@$data->lastName):ucfirst(@$data->user->staff->firstName).' '.ucfirst(@$data->user->staff->lastName),
            'profilePhoto'=>(!empty(@$data->profilePhoto)) && (!is_null(@$data->profilePhoto)) ? str_replace("public", "", URL::to('/')) . '/' . @$data->profilePhoto :(!empty(@$data->profilePhoto)) && (!is_null(@$data->profilePhoto))? str_replace("public", "", URL::to('/')) . '/' . @$data->user->profilePhoto:'',
            'specialization'=>(!empty(@$data->specialization))?@$data->specialization:@$data->user->staff->specialization->name,
            'flag'=>'#39B5C2'
        ];
    }
}
