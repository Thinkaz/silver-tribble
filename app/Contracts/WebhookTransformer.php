<?php

namespace App\Contracts;

interface WebhookTransformer
{
    public function transformPayload(array $payload, string $trigger, object $event): array;
}