<?php

namespace App\Contracts;

interface IDiscordEmbeddable
{
    public function toDiscordEmbed(): array;
}