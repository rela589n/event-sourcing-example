<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create(
            'messages',
            function (Blueprint $table) {
                $table->uuid('uuid')
                    ->primary();

                $table->string('status')
                    ->index();

                $table->string('content');

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

                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
