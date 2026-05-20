<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversaion;
use App\Models\CoversationMember;

class ChatController extends Controller
{
    function createConversation(Request $request)
    {
        $conversation = Conversation::create([
            'type' => 'private',
            'created_by' => auth()->id()
        ]);

        ConversationMember::insert([
            [
                'conversation_id' => $conversation->id,
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'conversation_id' => $conversation->id,
                'user_id' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        
        return response()->json($conversation);
    }

    function addParticipant(Request $request){
            ConversationMember::create([
                'conversation_id' => $request->conversation_id,
                'user_id' => $request->user_id
            ]);

            return response()->json(['success' => true]);
    }

    public function se
}
