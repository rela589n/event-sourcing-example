<?php

declare(strict_types=1);

use App\Models\Chat\Eloquent\Chat;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // inasmuch before chats had no creator reference, now we have to add this reference to all chats
        // we can get user_uuid from first event 'user_joined_chat', because it will have owners uuid in payload

        \Illuminate\Support\Facades\DB::unprepared(
            <<<SQL
            UPDATE chats
            SET user_uuid = sub.user_uuid
            FROM (select distinct on (chat_uuid) chat_uuid, (payload->>'user')::uuid as user_uuid
                  from chat_events
                  where chat_events.name = 'user_joined_chat'
                    and exists(select 1 from chats where chats.uuid = chat_events.chat_uuid and chats.user_uuid is null)
                  order by chat_uuid, "timestamp" desc) AS sub
            WHERE chats.uuid = sub.chat_uuid
              AND chats.user_uuid is null
        SQL
        );
    }

    public function down(): void
    {
        Chat::query()
            ->whereRaw('TRUE')
            ->update(['user_uuid' => null]);
    }
};
