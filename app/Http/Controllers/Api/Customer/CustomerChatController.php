<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTechnician;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class CustomerChatController extends Controller
{
public function rooms(Request $request)
    {
        $rooms = ChatRoom::with(['booking', 'technician'])
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $rooms]);
    }

    public function findOrCreateRoom(Request $request, $bookingId)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($bookingId);

        $assigned = BookingTechnician::where('booking_id', $booking->id)
            ->whereIn('status', ['accepted', 'working', 'completed'])
            ->latest()
            ->firstOrFail();

        $room = ChatRoom::firstOrCreate([
            'booking_id' => $booking->id,
            'customer_id' => $request->user()->id,
            'technician_id' => $assigned->technician_id,
        ]);

        return response()->json([
            'message' => 'Room chat siap',
            'data' => $room,
        ]);
    }

    public function messages(Request $request, $roomId)
    {
        $room = ChatRoom::where('customer_id', $request->user()->id)->findOrFail($roomId);

        $messages = ChatMessage::where('chat_room_id', $room->id)
            ->with('sender')
            ->oldest()
            ->get();

        return response()->json(['data' => $messages]);
    }

    public function sendMessage(Request $request, $roomId)
    {
        $room = ChatRoom::where('customer_id', $request->user()->id)->findOrFail($roomId);

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
