<?php

namespace App\Models;

use App\Casts\Html;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperChangelog
 */
class Changelog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content'
    ];

    protected $casts = [
        'content' => Html::class
    ];

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(ChangelogLabel::class, 'changelog_label');
    }
}
