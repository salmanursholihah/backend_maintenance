<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMessage>
 */
class ChatMessageFactory extends Factory
{
     public function definition(): array
    {
        return [
            'chat_room_id'    => ChatRoom::factory(),
            'sender_id'       => User::factory(),
            'message'         => fake()->sentence(fake()->numberBetween(3, 20)),
            'attachment'      => null,
            'attachment_type' => null,
            'is_read'         => fake()->boolean(70),
            'read_at'         => null,
        ];
    }
 
    public function unread(): static
    {
        return $this->state(['is_read' => false, 'read_at' => null]);
    }
 
    public function read(): static
    {
        return $this->state(['is_read' => true, 'read_at' => now()]);
    }
}
