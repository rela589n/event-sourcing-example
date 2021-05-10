<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table(
            'chats',
            function (Blueprint $table) {
                $table->foreignUuid('user_uuid')
                    ->nullable()
                    ->references('uuid')
                    ->on('users')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'chats',
            function (Blueprint $table) {
                $table->dropColumn('user_uuid');
            }
        );
    }
};
