<?php

namespace App\Support\WebhookTransformers;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTransformer;
use App\Models\Configuration;

class DiscordWebhookTransformer implements WebhookTransformer
{
    public function transformPayload(array $payload, string $trigger, object $event): array
    {
        if (!($event instanceof IDiscordEmbeddable)) return [];

        $configurations = Configuration::whereIn('key', ['site_name', 'site_logo', 'site_color'])
            ->get(['key', 'value'])->keyBy('key');

        // Take off the # of the hex color
        $color = substr($configurations->get('site_color')->value, 1);

        $embed = [
            'type' => 'rich',
            'color' => hexdec($color),
            'timestamp' => now()->toIso8601String(),

            'footer' => [
                'text' => $configurations->get('site_name')->value,
            ],
        ] + $event->toDiscordEmbed();

        return [
            'username' => $configurations->get('site_name')->value,
            'avatar_url' => $configurations->get('site_logo')->value,
            'embeds' => [$embed],
        ];
    }
}