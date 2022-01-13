<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\CommunicationService;

class CommunicationController extends Controller
{
    public function addCommunication(request $request)
    {
        return (new CommunicationService)->addCommunication( $request);
        
    }

    public function getCommunication(){
        return (new CommunicationService)->getCommunication();
    }

    public function addCallRecord(request $request){
        return (new CommunicationService)->addCallRecord($request);
    }
    
    public function inQueue(request $request){
        return (new CommunicationService)->inQueue($request);
    }
    public function goingOn(request $request){
        return (new CommunicationService)->goingOn($request);
    }
    public function completed(request $request){
        return (new CommunicationService)->completed($request);
    }

    public function callCountPerStaff(request $request){
        return (new CommunicationService)->callCountPerStaff($request);
    }

}
