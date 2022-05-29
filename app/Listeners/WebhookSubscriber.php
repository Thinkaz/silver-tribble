<?php

namespace App\Listeners;

use App\Contracts\WebhookTransformer;
use App\Contracts\WebhookTrigger;
use App\Models\Webhook;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class WebhookSubscriber
{
    private Client $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handleEvent(object $event)
    {
        if (!($event instanceof WebhookTrigger)) return;

        $trigger = array_search(get_class($event), Webhook::$triggers);
        if ($trigger === false) return;

        $payload = [
            'trigger' => $trigger,
            'object' => $event->toWebhookObject(),
        ];

        $webhooks = Webhook::whereJsonContains('triggers_on', $trigger)->get();

        /** @var Webhook $webhook */
        foreach ($webhooks as $webhook) {
            $transformedPayload = $this->transformPayload($payload, $webhook, $trigger, $event);
            $body = json_encode($transformedPayload);

            $signature = hash_hmac('sha256', $body, $webhook->secret);

            try {
                $this->client->post($webhook->url, [
                    'body' => $body,
                    'timeout' => 5,
                    'headers' => [
                        'X-Cosmo-Signature' => $signature,
                        'Content-Type' => 'application/json',
                    ],
                ]);
            } catch (GuzzleException $e) {
                Log::error($e->getMessage());
                continue;
            }
        }
    }

    private function transformPayload(array $payload, Webhook $webhook, string $trigger, object $event): array
    {
        if (!array_key_exists($webhook->type, Webhook::$payloadTransformers)) {
            return $payload;
        }

        /** @var WebhookTransformer $transformer */
        $transformer = app(Webhook::$payloadTransformers[$webhook->type]);
        if (!$transformer) return $payload;

        return $transformer->transformPayload($payload, $trigger, $event);
    }

    /**
     * Handle the event.
     *
     * @param Dispatcher $event
     * @return void
     */
    public function subscribe(Dispatcher $event)
    {
        $event->listen(
            array_values(Webhook::$triggers),
            [$this, 'handleEvent']
        );
    }
}
