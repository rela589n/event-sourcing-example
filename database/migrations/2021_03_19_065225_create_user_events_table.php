<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateUserEventsTable extends Migration
{
    public function up(): void
    {
        Schema::create(
            'user_events',
            function (Blueprint $table) {
                $table->id();

                $table->string('name')
                    ->index();

                $table->foreignUuid('user_uuid')
                    ->references('uuid')
                    ->on('users')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->jsonb('payload');

                $table->timestampTz('timestamp');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
}
