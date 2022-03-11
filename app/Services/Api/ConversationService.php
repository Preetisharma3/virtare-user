<?php

namespace App\Services\Api;

use Exception;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation\Conversation;
use App\Models\Communication\Communication;
use App\Models\Patient\PatientFamilyMember;
use App\Models\Conversation\ConversationMessage;
use App\Transformers\Conversation\ConversationTransformer;
use App\Transformers\Conversation\LatestMessageTransformer;
use App\Transformers\Conversation\ConversationListTransformer;



class ConversationService
{

    public function createConversation($request, $id)
    {
        try {
        if (!$id) {
            $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
            if ($familyMember == true) {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            } else {
                $senderId = auth()->user()->id;
            }
        } elseif ($id == auth()->user()->id) {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        } elseif ($id) {
            $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
            if ($familyMember == true) {
                $senderId = $id;
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
        $receiverId = $request->receiverId;
        $data = DB::table('communications')->where([['from', '=', $senderId],['referenceId','=',$receiverId],['messageTypeId','=',102]])->orWhere(function ($query)use($receiverId,$senderId) {
                $query->where([['from', '=', $receiverId],['referenceId',$senderId]])->where('messageTypeId','=',102);
            })->exists();
        if ($data == false) {
            $input = array(
                'udid' => Str::uuid()->toString(),
                'from' => $senderId,
                'referenceId' => $request->receiverId,
                'entityType' => 'staff',
                'messageTypeId' => 102,
                'subject' => 'SMS',
                'priorityId' => 72,
                'messageCategoryId' => 40,
                "createdBy" => Auth::id(),
            );
            $conversation = Communication::create($input);
            return fractal()->item($conversation)->transformWith(new ConversationListTransformer(true))->toArray();
        } elseif ($data == true) {
            $conversation =Communication::with('sender','receiver')->where([['from', '=', $senderId],['referenceId','=',$receiverId],['messageTypeId','=',102]])->orWhere(function ($query)use($receiverId,$senderId) {
                $query->where([['from', '=', $receiverId],['referenceId',$senderId]])->where('messageTypeId','=',102);
            })->first();
            return fractal()->item($conversation)->transformWith(new ConversationListTransformer(true))->toArray();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function allConversation($request, $id)
    {
        try {
            if (!$id) {
                $data = Communication::whereHas('conversationMessages')->where('from', auth()->user()->id)->orWhere('referenceId', auth()->user()->id)->get();
            } elseif ($id) {
                $data = Communication::whereHas('conversationMessages')->where('from', $id)->orWhere('referenceId', $id)->get();
            }
            return fractal()->collection($data)->transformWith(new ConversationListTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function sendMessage($request, $id)
    {
        try {
            if (!$id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                } else {
                    $senderId = auth()->user()->id;
                }
            } elseif ($id == auth()->user()->id) {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                    $senderId = $id;
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
            $input = array(
                'communicationId' => $request->conversationId,
                'message' => $request->message,
                'senderId' => $senderId,
                'type' => $request->type,
                "createdBy" => Auth::id(),
            );
            ConversationMessage::create($input);
            return response()->json([
                'message' => trans('messages.message_sent')
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function showConversation($request, $id)
    {
        try {
            $conversationId = $request->conversationId;
            if($conversationId){
                $input = Communication::where([['id', $conversationId]])->exists();
                if ($input == true) {
                    $data = ConversationMessage::where([['communicationId', $conversationId]])->get();
                } else {
                    $data = ConversationMessage::where([['communicationId', $conversationId]])->get();
                }
                return fractal()->collection($data)->transformWith(new ConversationTransformer)->toArray();
            }else{
                
                if (!$id) {
                    $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                    if ($familyMember == true) {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    } else {
                        $senderId = auth()->user()->id;
                    }
                } elseif ($id == auth()->user()->id) {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                } elseif ($id) {
                    $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                    if ($familyMember == true) {
                        $senderId = $id;
                    } else {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    }
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
                
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function latestMessage($request, $id)
    {

        try {
            if (!$id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                } else {
                    $senderId = auth()->user()->id;
                }
            } elseif ($id == auth()->user()->id) {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            } elseif ($id) {
                $familyMember = PatientFamilyMember::where([['userId', auth()->user()->id], ['isPrimary', 1]])->exists();
                if ($familyMember == true) {
                    $senderId = $id;
                } else {
                    return response()->json(['message' => trans('messages.unauthenticated')], 401);
                }
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
            $communicationId = $request->conversationId;
            $data = ConversationMessage::where([['isRead', 0], ['communicationId', $communicationId], ['senderId', "!=", $senderId]]);
            $newdata = $data->get();
            $data->update(['isRead' => 1]);
            return fractal()->collection($newdata)->transformWith(new LatestMessageTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
