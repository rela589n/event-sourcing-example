<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateChatEventsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'chat_events',
            function (Blueprint $table) {
                $table->id();

                $table->string('name')
                    ->index();

                $table->foreignUuid('chat_uuid')
                    ->references('uuid')
                    ->on('chats')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->jsonb('payload');

                $table->timestampTz('timestamp');
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('chat_events');
    }
}
