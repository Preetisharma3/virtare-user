<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationMessage;
use App\Transformers\Conversation\ConversationTransformer;
use App\Transformers\Conversation\ConversationListTransformer;



class ConversationService
{

    public function createConversation($request, $id)
    {
        try {
            $senderId = Auth::id();
            $receiverId = $request->receiverId;
            $data = Conversation::where([['senderId', $senderId], ['receiverId', $receiverId]])->exists();
            if ($data == false) {
                $input = array(
                    'senderId' => Auth::id(),
                    'receiverId' => $request->receiverId,
                    "createdBy" => Auth::id(),
                );
                $conversation = Conversation::create($input);
                return fractal()->item($conversation)->transformWith(new ConversationListTransformer(true))->toArray();
            } elseif ($data == true) {
                $conversation = Conversation::where([['senderId', $senderId], ['receiverId', $receiverId]])->first();
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
            $data = Conversation::whereHas('conversationMessages')->where('senderId', auth()->user()->id)->orWhere('receiverId',auth()->user()->id)->get();
            return fractal()->collection($data)->transformWith(new ConversationListTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function sendMessage($request, $id)
    {


        try {
            $input = array(
                'conversationId' => $request->conversationId,
                'message' => $request->message,
                'senderId' => auth()->user()->id,
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
            $receiverId = auth()->user()->id;
            $conversationId = $request->conversationId;
            $input = Conversation::where([['receiverId', $receiverId], ['id', $conversationId]])->orWhere([['senderId', $receiverId], ['id', $conversationId]])->exists();
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
            $conversationId = $request->conversationId;
                $data = ConversationMessage::where([['is_read', 0], ['conversation_id', $conversation_id], ['sender_id', "!=", auth()->user()->id]]);
                    if ($notPrimary->userAuthorization[0]->message == '1') {
                        $user_id = $id;
                        $conversation_id = $request->conversation_id;
                        $input = Conversation::where([['user_id', $user_id], ['id', $conversation_id]])->exists();
                        if ($input == true) {
                            $data = ConversationMessage::where([['is_read', 0], ['conversation_id', $conversation_id], ['sender_id', "!=", $user_id]]);
                        } else {
                            return response()->json(['message' => trans('messages.unauthenticated')]);
                        }
                    } else {
                        return response()->json(['message' => trans('messages.unauthenticated')], 401);
                    }
            $newdata = $data->get();
            $data->update(['isRead' => 1]);
            return fractal()->collection($newdata)->transformWith(new LatestMessageTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
