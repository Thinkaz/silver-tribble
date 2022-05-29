<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\Store\Order;

class OrderDelivered implements WebhookTrigger, IDiscordEmbeddable
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function toWebhookObject(): array
    {
        $this->order->loadMissing('buyer', 'package');

        return [
            'id' => $this->order->id,
            'receiver' => $this->order->receiver,
            'price' => $this->order->price,
            'assigned' => $this->order->assigned,
            'buyer' => $this->order->buyer->only('id', 'steamid', 'username', 'avatar', 'discord_id'),
            'package' => $this->order->package->withoutRelations(),
        ];
    }

    public function toDiscordEmbed(): array
    {
        return [
            'title' => 'Package Delivered',
            'description' => sprintf('Package %s purchased by %s has been delivered.', $this->order->package->name, $this->order->buyer->username),
            'author' => [
                'name' => $this->order->buyer->username,
                'url' => route('users.show', $this->order->buyer->steamid),
                'icon_url' => $this->order->buyer->avatar,
            ],
        ];
    }
}
