<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use Illuminate\Console\Command;

class PruneOldChatMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prune:old-chat-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prunes chat messages older than 1 day and not in the latest 50 messages.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $latest = ChatMessage::limit(50)
            ->get(['id'])
            ->map(fn (ChatMessage $chatMessage) => $chatMessage->id)
            ->toArray();

        $deleted = ChatMessage::whereNotIn('id', $latest)
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $this->info("Deleted $deleted old chat messages.");

        return 0;
    }
}
