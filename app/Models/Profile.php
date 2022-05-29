<?php

namespace App\Models;

use App\Casts\Html;
use App\Models\Profile\Comment;
use App\Models\Profile\Like;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperProfile
 */
class Profile extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'bio', 'signature', 'background_img'
    ];

    protected $casts = [
        'bio' => Html::class,
        'signature' => Html::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->bio) {
                $model->bio = 'Give your profile a fresh bio';
            }

            if (!$model->signature) {
                $model->signature = 'Signature...';
            }
        });
    }
}
