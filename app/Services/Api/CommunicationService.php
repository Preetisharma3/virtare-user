<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Communication\CallRecord;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Communication\Communication;
use App\Models\Communication\CommunicationMessage;
use App\Models\Communication\CommunicationCallRecord;
use App\Transformers\Communication\CallRecordTransformer;
use App\Transformers\Communication\CallStatusTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Communication\MessageTypeTransformer;
use App\Transformers\Communication\CommunicationTransformer;
use App\Transformers\Conversation\ConversationListTransformer;
use App\Transformers\Communication\CommunicationCountTransformer;
use App\Transformers\Communication\CommunicationSearchTransformer;

class CommunicationService
{
    //  create Communication
    public function addCommunication($request)
    {
        $reference = Helper::entity($request->entityType, $request->referenceId);
        if ($request->entityType == 'patient') {
            $newReference = Patient::where('id', $reference)->first();
        } elseif ($request->entityType == 'staff') {
            $newReference = Staff::where('id', $reference)->first();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')],  401);
        }
        $staffFrom = Staff::where('udid', $request->from)->first();
        $input = [
            'from' => $staffFrom->userId,
            'referenceId' => $newReference->userId,
            'messageTypeId' => $request->messageTypeId,
            'subject' => $request->subject,
            'priorityId' => $request->priorityId,
            'messageCategoryId' => $request->messageCategoryId,
            'createdBy' => 1,
            'entityType' => $request->entityType,
            'udid' => Str::uuid()->toString()
        ];
        $exdata = Communication::where([['from', $staffFrom->userId], ['referenceId', $newReference->userId]])->exists();
        if ($exdata == false) {
            $data = Communication::create($input);
            CommunicationMessage::create([
                'communicationId' => $data->id,
                'message' => $request->message,
                'createdBy' => $data->createdBy,
                'udid' => Str::uuid()->toString()
            ]);
            return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
        } elseif ($exdata == true) {
            return response()->json(['message' => 'Conversation already Exists!']);
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
    }

    // get Communication
    public function getCommunication($request)
    {
        if ($request->all) {
            $data = Communication::with('communicationMessage', 'patient', 'staff', 'globalCode', 'priority', 'type', 'staffs')->orderBy('createdAt', 'DESC')->get();
            return fractal()->collection($data)->transformWith(new CommunicationTransformer())->toArray();
        } else {
            $data = Communication::with('communicationMessage', 'patient', 'staff', 'globalCode', 'priority', 'type', 'staffs')->orderBy('createdAt', 'DESC')
                ->paginate(15, ['*'], 'page', $request->page);
            return fractal()->collection($data)->transformWith(new CommunicationTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        }
    }

    //Create A call Api
    public function addCallRecord($request)
    {
        $udid = Str::uuid()->toString();
        if (Auth::user()->roleId == 4) {
            $input = [
                'patientId' => auth()->user()->patient->id,
                'statusId' => $request->status,
                'createdBy' => Auth::id(),
                'udid' => $udid,
                'startTime',
                'endTime',
                'referenceId',
                'entityType'
            ];
        } elseif (Auth::user()->roleId == 3) {
            $patientId = Helper::entity('patient', $request->patient);
            $input = [
                'patientId' => $patientId,
                'statusId' => $request->status,
                'createdBy' => Auth::id(),
                'udid' => $udid,
                'startTime',
                'endTime',
                'referenceId',
                'entityType'
            ];
        }
        CommunicationCallRecord::create($input);
        $call = [];
        CallRecord::create();
        return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
    }

    //Call Status API's
    public function callStatus()
    {
        $data = CommunicationCallRecord::with('status')->select('callStatusId', DB::raw('count(*) as count'))->groupBy('callStatusId')->orderBy('createdAt', 'DESC')->get();
        return fractal()->collection($data)->transformWith(new CallStatusTransformer())->toArray();
    }

    // calls Per Staff API
    public function callCountPerStaff()
    {
        $data = CommunicationCallRecord::select('staffId', DB::raw('count(*) as count'))->groupBy('staffId')->orderBy('createdAt', 'DESC')->get();
        return fractal()->collection($data)->transformWith(new CallRecordTransformer())->toArray();
    }

    public function messageType()
    {
        $date = Carbon::today()->format('Y-m-d');
        $result = DB::select(
            "CALL communicationTypeCount('" . $date . "')",
        );
        return fractal()->collection($result)->transformWith(new MessageTypeTransformer())->toArray();
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
            $paginate = 1;
            $value = explode(',', $request->search);
            foreach ($value as $search) {
                $data = DB::select(
                    "CALL patientSearch('" . $search . "','" . $paginate . "')",
                );
                $page = $request->page;
                $offSet = ($page * $paginate) - $paginate;
                $currentPage = array_slice($data, $offSet, $paginate, true);
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator($currentPage, count($data), $paginate, $page);
                $route = URL::current();
                $paginator->setPath($route);
                return fractal()->collection($data)->transformWith(new CommunicationSearchTransformer())->paginateWith(new IlluminatePaginatorAdapter($paginator))->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
