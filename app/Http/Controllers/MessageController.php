<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function saveMessage(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'client_id' => 'nullable|exists:clients,id',
            'user_name' => 'nullable|string',
            'message_content' => 'required|string',
        ]);

        // Check if both are provided, which should not be the case
        if ($request->has('user_id') && $request->has('client_id')) {
            return response()->json(['status' => 'error', 'message' => 'Cannot set both user_id and client_id.'], 422);
        }

        // Save message
        $message = new Message();
        $message->user_id = $validatedData['user_id'] ?? null;
        $message->client_id = $validatedData['client_id'] ?? null;
        $message->user_name = $validatedData['user_name'];
        $message->message_content = $validatedData['message_content'];
        $message->save();

        return response()->json(['status' => 'success', 'message' => 'Message saved successfully']);
    }

    // Method to show all messages
    public function showMessages()
    {
       
        return response()->json([ 'messages' => 'showmessages']);
    }
}
