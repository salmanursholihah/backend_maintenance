<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
 
            $table->text('message')->nullable();          // isi pesan teks
            $table->string('attachment')->nullable();     // path file: storage/images/chat_attachments/
            $table->enum('attachment_type', [
                'image',
                'document',
            ])->nullable();
 
            $table->boolean('is_read')->default(false);  // sudah dibaca lawan bicara?
            $table->timestamp('read_at')->nullable();     // waktu dibaca
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
