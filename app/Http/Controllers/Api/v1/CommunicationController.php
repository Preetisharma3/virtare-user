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

}
