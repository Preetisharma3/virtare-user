<?php

namespace App\Services\Api;

use App\Models\Staff\Staff;
use Illuminate\Support\Facades\DB;
use App\Models\Communication\Communication;
use App\Models\Communication\CommunicationMessage;
use App\Models\Communication\CommunicationCallRecord;
use App\Transformers\Communication\CallRecordTransformer;
use App\Transformers\Communication\CallStatusTransformer;
use App\Transformers\Communication\MessageTypeTransformer;
use App\Transformers\Communication\CommunicationTransformer;
use App\Transformers\Communication\CommunicationProcedureTransformer;

class CommunicationService
{
    //  create Communication
    public function addCommunication($request, $id)
    {

        $staff = Staff::where('id', $id)->first();
        $input = [
            'from' => $staff->email,
            'to' => $request->to,
            'patientId' => $request->patientId,
            'messageTypeId' => $request->messageTypeId,
            'subject' => $request->subject,
            'priorityId' => $request->priorityId,
            'messageCategoryId' => $request->messageCategoryId,
            'createdBy' => 1,
        ];
        $data = Communication::create($input);
        CommunicationMessage::create([
            'communicationId' => $data->id,
            'message' => $request->message,
            'createdBy' => $data->createdBy,
        ]);
        return response()->json(['message' => 'created Successfully'], 200);
    }

    // get Communication
    public function getCommunication()
    {
        $data = Communication::with('communicationMessage', 'patient', 'staff', 'globalCode')->get();
        return fractal()->collection($data)->transformWith(new CommunicationTransformer())->toArray();
    }

    //Create A call Api
    public function addCallRecord($request)
    {
        $input = [
            'patientId' => $request->patient,
            'staffId' => $request->staff,
            'callStatusId' => $request->callStatus,
            'createdBy' => 1,
        ];
        CommunicationCallRecord::create($input);
        return response()->json(['message' => 'created Successfully'], 200);
    }

    //Call Status API's
    public function callStatus()
    {
        $data = CommunicationCallRecord::with('status')->select('callStatusId', DB::raw('count(*) as count'))->groupBy('callStatusId')->get();
        return fractal()->collection($data)->transformWith(new CallStatusTransformer())->toArray();
    }

    // calls Per Staff API
    public function callCountPerStaff()
    {
        $data = CommunicationCallRecord::select('staffId', DB::raw('count(*) as count'))->groupBy('staffId')->get();
        return fractal()->collection($data)->transformWith(new CallRecordTransformer())->toArray();
    }

    public function messageType()
    {
        $data = Communication::with('type')->select('messageTypeId', DB::raw('count(*) as count'))->groupBy('messageTypeId')->get();
        return fractal()->collection($data)->transformWith(new MessageTypeTransformer())->toArray();
    }

    public function communicationProcedure($request)
    {
        $postId = $request->providerId;
        $data = DB::select(
            'CALL getCommunicationsByProviderId(' . $postId . ')'
        );
        return fractal()->collection($data)->transformWith(new CommunicationProcedureTransformer())->toArray();
    }
}
