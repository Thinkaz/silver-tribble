<?php

namespace App\Providers;

use App\Events\RoleAssigned;
use App\Events\RoleUpdated;
use App\Listeners\UpdateUserDisplayRoleSubscriber;
use App\Listeners\UserNotificationSubscriber;
use App\Listeners\WebhookSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Steam\SteamExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SocialiteWasCalled::class => [
            SteamExtendSocialite::class,
            DiscordExtendSocialite::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserNotificationSubscriber::class,
        WebhookSubscriber::class,
        UpdateUserDisplayRoleSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
