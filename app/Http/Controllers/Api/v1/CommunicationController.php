<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\CommunicationService;

class CommunicationController extends Controller
{
    public function addCommunication(request $request)
    {
        return (new CommunicationService)->addCommunication($request);
    }

    public function getCommunication(Request $request)
    {
        return (new CommunicationService)->getCommunication($request);
    }

    public function callStatus(request $request)
    {
        return (new CommunicationService)->callStatus($request);
    }

    public function callCountPerStaff(request $request)
    {
        return (new CommunicationService)->callCountPerStaff($request);
    }

    public function messageType()
    {
        return (new CommunicationService)->messageType();
    }

    public function countCommunication(Request $request)
    {
        return (new CommunicationService)->communicationCount($request);
    }

    public function searchCommunication(Request $request)
    {
        return (new CommunicationService)->communicationSearch($request);
    }

    public function callUpdate(Request $request, $id)
    {
        return (new CommunicationService)->updateCall($request, $id);
    }
}
