<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateUserChatTable extends Migration
{
    public function up()
    {
        Schema::create(
            'user_chat',
            function (Blueprint $table) {
                $table->id();

                $table->foreignUuid('user_uuid')
                    ->references('uuid')
                    ->on('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreignUuid('chat_uuid')
                    ->references('uuid')
                    ->on('chats')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->unique(['user_uuid', 'chat_uuid']);
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('user_chat');
    }
}
