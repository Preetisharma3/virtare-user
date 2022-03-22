<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ConversationService;
use App\Http\Requests\Conversation\SendMessageRequest;
use App\Http\Requests\Conversation\ConversationRequest;

class ConversationController extends Controller
{
    public function conversation(ConversationRequest $request, $id = null)
    {
        return (new ConversationService)->createConversation($request, $id);
    }

    public function allConversation(request $request, $id = null)
    {
        return (new ConversationService)->allConversation($request, $id);
    }

    public function conversationMessage(Request $request, $id = null)
    {
        return (new ConversationService)->sendMessage($request, $id);
    }

    public function showConversation(request $request, $id = null)
    {
        return (new ConversationService)->showConversation($request, $id);
    }

    public function latestMessage(request $request, $id = null)
    {
        return (new ConversationService)->latestMessage($request, $id);
    }
}
