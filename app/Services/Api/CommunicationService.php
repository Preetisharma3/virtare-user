<?php

namespace App\Services\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Communication\Communication;
use App\Models\Communication\CommunicationMessage;
use App\Models\Communication\CommunicationCallRecord;
use App\Transformers\Communication\CallRecordTransformer;
use App\Transformers\Communication\CallStatusTransformer;
use App\Transformers\Communication\MessageTypeTransformer;
use App\Transformers\Communication\CommunicationTransformer;
use App\Transformers\Communication\CommunicationCountTransformer;

class CommunicationService
{
    //  create Communication
    public function addCommunication($request)
    {
        $udid = Str::uuid()->toString();
        $input = [
            'from' => $request->from,
            'referenceId' => $request->referenceId,
            'messageTypeId' => $request->messageTypeId,
            'subject' => $request->subject,
            'priorityId' => $request->priorityId,
            'messageCategoryId' => $request->messageCategoryId,
            'createdBy' => 1,
            'entityType' => $request->entityType,
            'udid' => $udid
        ];
        $data = Communication::create($input);
        CommunicationMessage::create([
            'communicationId' => $data->id,
            'message' => $request->message,
            'createdBy' => $data->createdBy,
            'udid' => $udid
        ]);
        return response()->json(['message' => 'created Successfully'], 200);
    }

    // get Communication
    public function getCommunication()
    {
        $data = Communication::with('communicationMessage', 'patient', 'staff', 'globalCode', 'priority', 'type','staffs')->get();
        return fractal()->collection($data)->transformWith(new CommunicationTransformer())->toArray();
    }

    //Create A call Api
    public function addCallRecord($request)
    {
        $udid = Str::uuid()->toString();
        $input = [
            'patientId' => $request->patient,
            'staffId' => $request->staff,
            'callStatusId' => $request->callStatus,
            'createdBy' => 1,
            'udid' => $udid
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
        $data = Communication::whereDate('createdAt', Carbon::now())->with('type')->first();
        $count = Communication::select(DB::raw('count(id) as count,HOUR(createdAt) as time'))->groupBy(DB::raw('hour(createdAt)', 'count'))->get();
        $result = [
            'data' => $data,
            'count' => $count,
        ];
        return fractal()->item($result)->transformWith(new MessageTypeTransformer())->toArray();
    }

    public function communicationCount($request)
    {
        try {
            $date2 = Carbon::parse($request->date)->setTimezone('UTC');
            $today = CommunicationCallRecord::whereDate('createdAt', $date2)->count();
            $yesterday = CommunicationCallRecord::whereDate('createdAt',  $date2->subDays(1))->count();
            $tomorrow = CommunicationCallRecord::whereDate('createdAt', $date2->addDays(1))->count();
            $week = CommunicationCallRecord::whereDate('createdAt', $date2->subDays(7))->count();
            $Today = ['text' => 'Today', 'count' => $today, 'backgroundColor' => '#91BDFF', 'textColor' => '#FFFFFF'];
            $Yesterday = ['text' => 'Yesterday', 'count' => $yesterday, 'backgroundColor' => '#8E60FF', 'textColor' => '#FFFFFF'];
            $Tomorrow = ['text' => 'Tomorrow', 'count' => $tomorrow, 'backgroundColor' => '#90EEF5', 'textColor' => '#FFFFFF'];
            $Week = ['text' => 'Week', 'count' => $week, 'backgroundColor' => '#FFA800', 'textColor' => '#FFFFFF'];
            $result = [
                $Today, $Yesterday, $Tomorrow, $Week
            ];
            return fractal()->collection($result)->transformWith(new CommunicationCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function communicationSearch($request)
    {
        try {
            $value = explode(',', $request->search);
            foreach($value as $search) {
                $data = Communication::whereHas('staff', function ($query) use ($search) {
                        $query->whereRaw("MATCH(firstName)AGAINST($search)");
                })->orwhereHas('patient', function ($q) use ($search) {
                        $q->whereRaw("MATCH(firstName)AGAINST($search)");
                })->orwhereHas('type', function ($q) use ($search) {
                        $q->whereRaw("MATCH(name)AGAINST($search)");
                })->orwhereHas('priority', function ($q) use ($search) {
                        $q->whereRaw("MATCH(name)AGAINST($search)");
                })->orwhereHas('globalCode', function ($q) use ($search) {
                        $q->whereRaw("MATCH(name)AGAINST($search)");
                })->get();
             //   dd($data);
                return fractal()->collection($data)->transformWith(new CommunicationTransformer())->toArray();
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
