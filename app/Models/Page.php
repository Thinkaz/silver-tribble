<?php

namespace App\Models;

use App\Casts\Html;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPage
 */
class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'content'
    ];

    protected $casts = [
        'content' => Html::class,
    ];
}
