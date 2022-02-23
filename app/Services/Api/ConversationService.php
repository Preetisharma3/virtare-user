<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationMessage;
use App\Models\Patient\PatientFamilyMember;
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
            $data = Conversation::where([['senderId', $senderId], ['receiverId', $receiverId]])->exists();
            if ($data == false) {
                $input = array(
                    'udid' => Str::uuid()->toString(),
                    'senderId' => $senderId,
                    'receiverId' => $request->receiverId,
                    "createdBy" => Auth::id(),
                );
                $conversation = Conversation::create($input);
                return fractal()->item($conversation)->transformWith(new ConversationListTransformer(true))->toArray();
            } elseif ($data == true) {
                $conversation = Conversation::where([['senderId', $senderId], ['receiverId', $receiverId]])->with('sender', 'receiver')->first();
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
                $data = Conversation::whereHas('conversationMessages')->where('senderId', auth()->user()->id)->orWhere('receiverId', auth()->user()->id)->get();
            } elseif ($id) {
                $data = Conversation::whereHas('conversationMessages')->where('senderId', $id)->orWhere('receiverId', $id)->get();
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
                'conversationId' => $request->conversationId,
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
            $conversationId = $request->conversationId;
            $input = Conversation::where([['receiverId', $senderId], ['id', $conversationId]])->orWhere([['senderId', $senderId], ['id', $conversationId]])->exists();
            if ($input == true) {
                $data = ConversationMessage::where([['conversationId', $conversationId]])->get();
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')]);
            }
            return fractal()->collection($data)->transformWith(new ConversationTransformer)->toArray();
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
            $conversationId = $request->conversationId;
            $data = ConversationMessage::where([['isRead', 0], ['conversationId', $conversationId], ['senderId', "!=", $senderId]]);
            $newdata = $data->get();
            $data->update(['isRead' => 1]);
            return fractal()->collection($newdata)->transformWith(new LatestMessageTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
