<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Communication\Communication;
use App\Models\Communication\CommunicationMessage;
use App\Models\Communication\CallRecord;
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
        $staffFromId = $staffFrom->userId;
        $newReferenceId = $newReference->userId;
        $input = [
            'from' => $staffFromId,
            'referenceId' => $newReferenceId,
            'messageTypeId' => $request->messageTypeId,
            'subject' => $request->subject,
            'priorityId' => $request->priorityId,
            'messageCategoryId' => $request->messageCategoryId,
            'createdBy' => 1,
            'entityType' => $request->entityType,
            'udid' => Str::uuid()->toString()
        ];
        if ($request->messageTypeId == '102') {

            $exdata = DB::table('communications')->where([['from', '=', $staffFromId], ['referenceId', '=', $newReferenceId], ['messageTypeId', '=', 102]])->orWhere(function ($query) use ($staffFromId, $newReferenceId) {
                $query->where([['from', '=', $newReferenceId], ['referenceId', $staffFromId]])->where('messageTypeId', '=', 102);
            })->exists();
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
        } else {
            $data = Communication::create($input);
            CommunicationMessage::create([
                'communicationId' => $data->id,
                'message' => $request->message,
                'createdBy' => $data->createdBy,
                'udid' => Str::uuid()->toString()
            ]);
            return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
        }
    }

    // get Communication
    public function getCommunication($request)
    {
        if ($request->all) {
            $data = Communication::with('communicationMessage', 'patient', 'staff', 'globalCode', 'priority', 'type', 'staffs')->orderBy('createdAt', 'DESC')->get();
            return fractal()->collection($data)->transformWith(new CommunicationTransformer())->toArray();
        } else {
            if (auth()->user()->roleId == 3) {
                $data = Communication::whereHas('sender', function ($query) {
                    $query->where('from', auth()->user()->id)->orWhere('referenceId', auth()->user()->id);
                })->with('communicationMessage', 'patient', 'staff', 'globalCode', 'priority', 'type', 'staffs')->orderBy('createdAt', 'DESC')
                    ->paginate(15, ['*'], 'page', $request->page);
            } else {
                $data = Communication::with('communicationMessage', 'patient', 'staff', 'globalCode', 'priority', 'type', 'staffs')->orderBy('createdAt', 'DESC')
                    ->paginate(15, ['*'], 'page', $request->page);
            }
            return fractal()->collection($data)->transformWith(new CommunicationTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        }
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
        $data = CallRecord::select('staffId', DB::raw('count(*) as count'))->groupBy('staffId')->orderBy('createdAt', 'DESC')->get();
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


    // Call Update 
    public function updateCall($request, $id)
    {
        $start = '';
        $end = '';
        if ($request->status == 'start') {
            $start = Carbon::now();
        } elseif ($request->status == 'end') {
            $end = Carbon::now();
        }
        $comm = array();
        if (!empty($start)) {
            $comm['startTime'] = $start;
        }
        if (!empty($end)) {
            $comm['endTime'] = $end;
        }
        $comm['updatedBy'] = Auth::id();
        CommunicationCallRecord::where('referenceId', $id)->update($comm);
        return response()->json(['message' => trans('messages.updatedSuccesfully')]);
    }
}
