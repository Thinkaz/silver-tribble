<?php

namespace App\Contracts;

interface WebhookTrigger
{
    public function toWebhookObject(): array;
}