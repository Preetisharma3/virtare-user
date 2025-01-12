<?php

namespace App\Services\Api;

use Exception;
use App\Models\Contact\ContactText;
use App\Models\Contact\RequestCall;
use App\Models\Contact\ContactEmail;
use Illuminate\Support\Facades\Auth;

class ContactService
{
    public function requestCall($request)
    {
        try {
            $id = Auth::id();
            RequestCall::create([
                "userId" => $id,
                "contactTiming" => $request->contactTiming,
                "messageStatusId" => 47,
                "createdBy" => Auth::id(),
            ]);
            return response()->json(['message' => trans('messages.callRequest')],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
    public function contactMessage($request)
    {
        try {
            $id = Auth::id();
            ContactText::create([
                "userId" => $id,
                "message" => $request->message,
                "messageStatusId" => 47,
                "createdBy" => Auth::id(),
            ]);

            return response()->json(['message' => trans('messages.message_request')],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
    public function contactEmail($request)
    {
        try {
            $data = $request->all();
            $status = 1;
            // Mail::to($data['email'])->send(new Contact($data));
            $this->sendData($request, $status);
            return response()->json(['message' => trans('messages.email_request')], 200);
        } catch (Exception $e) {
            $status = 0;
            $this->sendData($request, $status);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function sendData($data, $status)
    {
        try {
            ContactEmail::create([
                "userId" => Auth::id(),
                "name" => $data->name,
                "email" => $data->email,
                "message" => $data->message,
                "status" => $status,
                "createdBy" => Auth::id(),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
