<?php

namespace App\Models\Forums;

use App\Casts\Html;
use App\Events\PostCreated;
use App\Models\User;
use App\Traits\Reactable;
use App\Traits\Reputable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use Reactable, SoftDeletes, Reputable;

    protected $fillable = [
        'content', 'user_id'
    ];

    protected $casts = [
        'user_id' => 'int',
        'content' => Html::class
    ];

    protected $dispatchesEvents = [
        'created' => PostCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
