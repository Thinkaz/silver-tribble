<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\Store\Order;
use App\Models\Store\Transactions\CoinbaseTransaction;
use App\Models\Store\Transactions\PayPalTransaction;
use App\Models\Store\Transactions\StripeTransaction;

class OrderSucceeded implements WebhookTrigger, IDiscordEmbeddable
{
    private static array $transactionTypeNames = [
        PayPalTransaction::class => 'PayPal',
        StripeTransaction::class => 'Stripe',
        CoinbaseTransaction::class => 'Coinbase',
    ];

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function toWebhookObject(): array
    {
        $this->order->loadMissing('package', 'buyer', 'transaction');

        return [
            'id' => $this->order->id,
            'receiver' => $this->order->receiver,
            'price' => $this->order->price,
            'assigned' => $this->order->assigned,
            'buyer' => $this->order->buyer->only('id', 'steamid', 'username', 'avatar', 'discord_id'),
            'package' => $this->order->package->withoutRelations(),
            'transaction' => $this->order->transaction->withoutRelations(),
        ];
    }

    public function toDiscordEmbed(): array
    {
        $fields = [
            [
                'name' => 'Price',
                'value' => $this->order->price,
                'inline' => true,
            ],
            [
                'name' => 'Payment Gateway',
                'value' => self::$transactionTypeNames[get_class($this->order->transaction)],
                'inline' => true,
            ],
            [
                'name' => 'Was Assigned',
                'value' => $this->order->assigned ? 'Yes' : 'No',
                'inline' => true,
            ],
        ];

        if ($this->order->receiver !== $this->order->buyer->steamid) {
            $fields[] = [
                'name' => 'Receiver',
                'value' => $this->order->receiver,
                'inline' => true,
            ];
        }

        return [
            'title' => sprintf("%s successfully purchased %s", $this->order->buyer->username, $this->order->package->name),
            'description' => 'Payment has succeeded and is now waiting for integration to handle the actions',
            'author' => [
                'name' => $this->order->buyer->username,
                'url' => route('users.show', $this->order->buyer->steamid),
                'icon_url' => $this->order->buyer->avatar,
            ],
            'fields' => $fields,
        ];
    }
}
