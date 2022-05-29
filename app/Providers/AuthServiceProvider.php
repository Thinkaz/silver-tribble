<?php

namespace App\Providers;

use App\Models\Forums\Post;
use App\Models\Forums\Thread;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\Manage\RolePolicy;
use App\Policies\Manage\UserPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\ThreadPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Profile::class => ProfilePolicy::class,
        Thread::class => ThreadPolicy::class,
        Post::class => PostPolicy::class,
        Profile\Comment::class => CommentPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::after(function (User $user, string $ability) {
            return $user->steamid === config('cosmo.licensee');
        });
    }
}
