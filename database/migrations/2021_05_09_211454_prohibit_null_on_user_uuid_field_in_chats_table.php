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
                $table->uuid('user_uuid')
                    ->nullable(false)
                    ->change();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'chats',
            function (Blueprint $table) {
                $table->uuid('user_uuid')
                    ->nullable(true)
                    ->change();
            }
        );
    }
};
