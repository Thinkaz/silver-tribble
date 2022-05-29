<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\ChatMessage;
use Illuminate\Foundation\Events\Dispatchable;

class ChatMessageSent implements WebhookTrigger, IDiscordEmbeddable
{
    use Dispatchable;

    public ChatMessage $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function toWebhookObject(): array
    {
        $this->message->loadMissing('user');

        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user' => $this->message->user->only('id', 'steamid', 'username', 'avatar', 'discord_id'),
            'created_at' => $this->message->created_at,
        ];
    }

    public function toDiscordEmbed(): array
    {
        return [
            'title' => 'Chat Message',
            'description' => $this->message->message,
            'author' => [
                'name' => $this->message->user->username,
                'url' => route('users.show', $this->message->user->steamid),
                'icon_url' => $this->message->user->avatar,
            ],
        ];
    }
}