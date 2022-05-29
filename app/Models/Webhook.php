<?php

namespace App\Models;

use App\Events\ChangelogCreated;
use App\Events\ChatMessageSent;
use App\Events\OrderDelivered;
use App\Events\OrderSucceeded;
use App\Events\PostCreated;
use App\Events\ThreadActionExecuted;
use App\Events\ThreadCreated;
use App\Support\WebhookTransformers\DiscordWebhookTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperWebhook
 */
class Webhook extends Model
{
    use HasFactory;

    private const SECRET_LENGTH = 20;

    public const TYPE_DISCORD = 0;
    public const TYPE_CUSTOM = 1;

    /**
     * A map of events a webhook is able to trigger on
     *
     * @var array<string, class-string|string>
     */
    public static array $triggers = [
        'thread.created' => ThreadCreated::class,
        'thread.action' => ThreadActionExecuted::class,
        'post.created' => PostCreated::class,
        'order.succeeded' => OrderSucceeded::class,
        'order.delivered' => OrderDelivered::class,
        'changelog.created' => ChangelogCreated::class,
        'chat.message' => ChatMessageSent::class,
    ];

    /**
     * A map of payload transformers per type
     *
     * @var array<string, class-string>
     */
    public static array $payloadTransformers = [
        self::TYPE_DISCORD => DiscordWebhookTransformer::class,
    ];

    protected $fillable = [
        'url', 'type', 'description', 'triggers_on'
    ];

    protected $casts = [
        'triggers_on' => 'array',
        'secret' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Webhook $webhook) {
            $webhook->secret = Str::random(self::SECRET_LENGTH);
        });
    }
}
