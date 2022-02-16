<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationMessage;
use App\Transformers\Conversation\ConversationTransformer;
use App\Transformers\Conversation\LatestMessageTransformer;
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
                    'udid'=>Str::uuid()->toString(),
                    'senderId' => Auth::id(),   
                    'receiverId' => $request->receiverId,
                    "createdBy" => Auth::id(),
                );
                $conversation = Conversation::create($input);
                return fractal()->item($conversation)->transformWith(new ConversationListTransformer(true))->toArray();
            } elseif ($data == true) {
                $conversation = Conversation::where([['senderId', $senderId], ['receiverId', $receiverId]])->with('sender','receiver')->first();
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
                $data = ConversationMessage::where([['isRead', 0], ['conversationId', $conversationId], ['senderId', "!=", auth()->user()->id]]);
                $newdata = $data->get();
                $data->update(['isRead' => 1]);
                return fractal()->collection($newdata)->transformWith(new LatestMessageTransformer)->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
