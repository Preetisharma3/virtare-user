<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Communication\CallRecord;
use App\Models\Communication\CallRecords;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
use App\Transformers\Communication\CommunicationCountTransformer;
use App\Transformers\Communication\CommunicationSearchTransformer;

class CommunicationService
{
    //  create Communication
    public function addCommunication($request)
    {
        $reference = Helper::entity($request->entityType, $request->referenceId);
        $staffFrom = Helper::entity('staff', $request->from);
        $input = [
            'from' => $staffFrom,
            'referenceId' => $reference,
            'messageTypeId' => $request->messageTypeId,
            'subject' => $request->subject,
            'priorityId' => $request->priorityId,
            'messageCategoryId' => $request->messageCategoryId,
            'createdBy' => 1,
            'entityType' => $request->entityType,
            'udid' => Str::uuid()->toString()
        ];
        $data = Communication::create($input);
        CommunicationMessage::create([
            'communicationId' => $data->id,
            'message' => $request->message,
            'createdBy' => $data->createdBy,
            'udid' => Str::uuid()->toString()
        ]);
        return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
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

    //Create A call Api beyween doctor and patient
    public function communicationCallRecordAdd($request)
    {
        $patient = Helper::entity('patient', $request->input('patient'));
        $startTime = Helper::time($request->input('startTime'));
        $endTime = Helper::time($request->input('endTime'));
        $input = [
            'patientId' => $patient, 'statusId' => $request->input('status'), 'startTime' => $startTime,
            'endTime' => $endTime, 'referenceId' => $request->input('reference'), 'entityType' => $request->input('entityType'),
            'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
        ];
        $communication = CommunicationCallRecord::create($input);
        $data = [
            'udid' => Str::uuid()->toString(), 'staffId' => auth()->user()->id, 'communicationCallRecordId' => $communication->id,
            'createdBy' => Auth::id()
        ];
        CallRecord::create($data);
        return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
    }

    // call between multiple doctors and patient
    public function communicationCallRecordCreate($request)
    {
        $patient = Helper::entity('patient', $request->input('patient'));
        $startTime = Helper::time($request->input('startTime'));
        $endTime = Helper::time($request->input('endTime'));
        $input = [
            'patientId' => $patient, 'statusId' => $request->input('status'), 'startTime' => $startTime,
            'endTime' => $endTime, 'referenceId' => $request->input('reference'), 'entityType' => $request->input('entityType'),
            'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()
        ];
        $communication = CommunicationCallRecord::create($input);
        $staffId=$request->input('staff');
        foreach ($staffId as $value) {
        $staff = Helper::entity('staff', $value);
            $data = [
                'udid' => Str::uuid()->toString(), 'staffId' => $staff, 'communicationCallRecordId' => $communication->id,
                'createdBy' => Auth::id()
            ];
            CallRecord::create($data);
        }
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
