<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class TechnicianChatController extends Controller
{
public function rooms(Request $request)
    {
        $rooms = ChatRoom::with(['booking', 'customer'])
            ->where('technician_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $rooms]);
    }

    public function messages(Request $request, $roomId)
    {
        $room = ChatRoom::where('technician_id', $request->user()->id)->findOrFail($roomId);

        $messages = ChatMessage::where('chat_room_id', $room->id)
            ->with('sender')
            ->oldest()
            ->get();

        return response()->json(['data' => $messages]);
    }

    public function sendMessage(Request $request, $roomId)
    {
        $room = ChatRoom::where('technician_id', $request->user()->id)->findOrFail($roomId);

        $data = $request->validate([
            'message' => ['nullable', 'string'],
            'attachment' => ['nullable', 'string'],
        ]);

        $message = ChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_id' => $request->user()->id,
            'message' => $data['message'] ?? null,
            'attachment' => $data['attachment'] ?? null,
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim',
            'data' => $message,
        ], 201);
    }}
