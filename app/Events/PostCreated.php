<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\Forums\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated implements WebhookTrigger, IDiscordEmbeddable
{
    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function toWebhookObject(): array
    {
        $this->post->loadMissing('thread', 'user');

        return [
            'id' => $this->post->id,
            'content' => $this->post->content,
            'thread' => $this->post->thread->withoutRelations(),
            'user' => $this->post->user->only('id', 'steamid', 'username', 'avatar', 'discord_id'),
        ];
    }

    public function toDiscordEmbed(): array
    {
        return [
            'title' => 'Post created by ' . $this->post->user->username,
            'url' => route('forums.posts.show', $this->post->id),
            'author' => [
                'name' => $this->post->user->username,
                'url' => route('users.show', $this->post->user->steamid),
                'icon_url' => $this->post->user->avatar,
            ],
            'fields' => [
                [
                    'name' => 'Thread',
                    'value' => $this->post->thread->title,
                    'inline' => true,
                ],
                [
                    'name' => 'Board',
                    'value' => $this->post->thread->board->name,
                    'inline' => true,
                ],
            ],
        ];
    }
}
