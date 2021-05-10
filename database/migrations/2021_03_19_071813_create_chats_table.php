<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateChatsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'chats',
            function (Blueprint $table) {
                $table->uuid('uuid')
                    ->primary();

                $table->string('name')
                    ->index();

                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
