<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::unprepared(
            <<<SQL
            UPDATE chat_events
            SET payload = chat_events.payload || chats_upd_sub.user_json
            FROM (
                     SELECT chat_uuid, jsonb_build_object('user', user_uuid) AS user_json
                     FROM (SELECT uuid AS chat_uuid, user_uuid FROM chats) AS chats_sub
                 ) AS chats_upd_sub
            WHERE chat_events.name = 'chat_created'
              AND chat_events.chat_uuid = chats_upd_sub.chat_uuid;
        SQL
        );
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::unprepared(
            <<<SQL
            UPDATE chat_events
                SET payload = chat_events.payload - 'user'
            WHERE chat_events.name = 'chat_created';
            SQL
        );
    }
};
