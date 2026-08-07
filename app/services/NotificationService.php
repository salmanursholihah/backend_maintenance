<?php

namespace App\Services;

use App\Models\AppNotification;

class NotificationService
{
    public static function send(
        int $userId,
        string $title,
        string $message,
        ?string $type = null,
        ?int $bookingId = null,
    ): AppNotification {
        return AppNotification::create([
            'user_id'    => $userId,
            'booking_id' => $bookingId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'is_read'    => false,
        ]);
    }
}


