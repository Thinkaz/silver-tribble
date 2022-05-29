<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\Forums\Thread;
use Illuminate\Queue\SerializesModels;

class ThreadCreated implements WebhookTrigger, IDiscordEmbeddable
{
    public Thread $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function toWebhookObject(): array
    {
        $this->thread->loadMissing('user', 'board');

        return [
            'id' => $this->thread->id,
            'title' => $this->thread->title,
            'content' => $this->thread->content,
            'board' => $this->thread->board->withoutRelations(),
            'user' => $this->thread->user->only('id', 'steamid', 'username', 'avatar', 'discord_id'),
        ];
    }

    public function toDiscordEmbed(): array
    {
        return [
            'title' => 'Thread posted by ' . $this->thread->user->username,
            'description' => $this->thread->title,
            'url' => route('forums.threads.show', $this->thread->id),
            'author' => [
                'name' => $this->thread->user->username,
                'url' => route('users.show', $this->thread->user->steamid),
                'icon_url' => $this->thread->user->avatar,
            ],
            'fields' => [
                [
                    'name' => 'Board',
                    'value' => $this->thread->board->name,
                    'inline' => true,
                ],
                [
                    'name' => 'Category',
                    'value' => $this->thread->board->category->name,
                    'inline' => true,
                ],
            ],
        ];
    }
}
