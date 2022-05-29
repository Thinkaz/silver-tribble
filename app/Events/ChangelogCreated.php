<?php

namespace App\Events;

use App\Contracts\IDiscordEmbeddable;
use App\Contracts\WebhookTrigger;
use App\Models\Changelog;
use App\Models\ChangelogLabel;

class ChangelogCreated implements WebhookTrigger, IDiscordEmbeddable
{
    private Changelog $changelog;

    public function __construct(Changelog $changelog)
    {
        $this->changelog = $changelog;
    }

    public function toWebhookObject(): array
    {
        $this->changelog->load('labels');

        return $this->changelog->only('id', 'title', 'content');
    }

    public function toDiscordEmbed(): array
    {
        $labels = $this->changelog->labels->map(fn(ChangelogLabel $label) => $label->name);

        return [
            'title' => 'Changelog published: ' . $this->changelog->title,
            'url' => route('changelogs'),
            'description' => $labels->isNotEmpty() ? sprintf("`%s`", $labels->join("`, `")) : 'No tags',
        ];
    }
}
