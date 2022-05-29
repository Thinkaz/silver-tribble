<?php

namespace App\Models\Forums;

use App\Casts\Html;
use App\Events\ThreadCreated;
use App\Models\User;
use App\Traits\Reactable;
use App\Traits\Reputable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperThread
 */
class Thread extends Model
{
    use Reactable, SoftDeletes, HasFactory, Reputable;

    protected $fillable = [
        'title', 'content', 'user_id', 'stickied', 'locked', 'board_id'
    ];

    protected $casts = [
        'user_id' => 'int',
        'content' => Html::class,
        'locked' => 'bool'
    ];

    protected $dispatchesEvents = [
        'created' => ThreadCreated::class,
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function latestPost(): HasOne
    {
        return $this->hasOne(Post::class)->ofMany();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function(Thread $thread) {
            $thread->posts()->delete();
        });
    }
}
