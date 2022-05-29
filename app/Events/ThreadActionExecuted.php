<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\Forums\Thread;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadActionExecuted implements WebhookTrigger, IDiscordEmbeddable
{
    use Dispatchable;

    public Thread $thread;
    public User $user;
    public string $action;

    public function __construct(Thread $thread, User $user, string $action)
    {
        $this->thread = $thread;
        $this->user = $user;
        $this->action = $action;
    }

    public function toWebhookObject(): array
    {
        $this->thread->loadMissing('user', 'board');

        return [
            'action' => $this->action,
            'user' => $this->user->only('id', 'steamid', 'username', 'avatar', 'discord_id'),
            'thread' => [
                'id' => $this->thread->id,
                'title' => $this->thread->title,
                'content' => $this->thread->content,
                'board' => $this->thread->board->withoutRelations(),
                'user' => $this->thread->user->only('id', 'steamid', 'username', 'avatar', 'discord_id', 'role_id'),
            ],
        ];
    }

    public function toDiscordEmbed(): array
    {
        return [
            'title' => "Thread $this->action by {$this->user->username}",
            'description' => $this->thread->title,
            'url' => route('forums.threads.show', $this->thread->id),
            'author' => [
                'name' => $this->thread->user->username,
                'url' => route('users.show', $this->thread->user->steamid),
                'icon_url' => $this->thread->user->avatar,
            ],
        ];
    }
}
