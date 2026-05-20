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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId("conversation_id")->contrained()->onDelete("cacade"); 
            $table->foreignId("sender_id")->constrained()->onDelete("cascade");  
            $table->enum("message_type",["text", "image", "video", "file"]);
            $table->text("content");
            $table->boolean("is_edited");
            $table->dateTime("deleted_at");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
