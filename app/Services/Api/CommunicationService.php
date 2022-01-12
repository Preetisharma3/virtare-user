<?php

namespace App\Services\Api;

use App\Models\Communication\Communication;
use App\Models\Communication\CommunicationMessage;
use App\Transformers\Communication\CommunicationTransformer;

class CommunicationService
{
    public function addCommunication($request){
        $input = [
            'from'=>auth()->user()->email,
            'to'=>$request->to,
            'patientId'=>$request->patientId,
            'subject'=>$request->subject,
            'priorityId'=>$request->priorityId,
            'messageCategoryId'=>$request->messageCategoryId,
            'createdBy'=> auth()->user()->id,
        ];
        $data = Communication::create($input);
        CommunicationMessage::create([
            'communicationId'=>$data->id,
            'message'=>$request->message,
            'createdBy'=> $data->createdBy,
        ]);
        return response()->json(['message'=>'created Successfully'],200);
    }


    public function getCommunication(){
        $data = Communication::with('communicationMessage','patient','staff','globalCode')->get();
        return fractal()->collection($data)->transformWith(new CommunicationTransformer())->toArray();
        
    }
}
