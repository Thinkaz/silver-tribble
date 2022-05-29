<?php

namespace App\Models\Profile;

use App\Casts\Html;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    protected $fillable = ['content', 'user_id'];

    protected $casts = [
        'user_id' => 'int',
        'content' => Html::class
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
