<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Models\Forums\Thread;
use App\Models\Profile\Comment;
use App\Models\Store\Order;
use App\Traits\Reputable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use tehwave\Achievements\Traits\Achiever;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use Notifiable, Achiever, HasFactory, HasRoles, Reputable;

    protected $fillable = [
        'username', 'steamid', 'avatar', 'discord_id'
    ];

    protected $hidden = [
        'remember_token'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];

    public function displayRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'display_role_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return static::firstWhere('steamid', $value);
    }

    public function ban($reason, $platforms = ['*']): Model
    {
        return $this->bans()->create([
            'reason' => $reason,
            'platforms' => $platforms
        ]);
    }

    protected static function boot()
    {
        static::created(function ($user) {
            $user->profile()->create();
        });

        parent::boot();
    }
}
