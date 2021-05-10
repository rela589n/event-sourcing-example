<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateMessageEventsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'message_events',
            function (Blueprint $table) {
                $table->id();

                $table->string('name')
                    ->index();

                $table->foreignUuid('message_uuid')
                    ->references('uuid')
                    ->on('messages')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->jsonb('payload');

                $table->timestampTz('timestamp');
            }
        );

    }

    public function down()
    {
        Schema::dropIfExists('message_events');
    }
}
