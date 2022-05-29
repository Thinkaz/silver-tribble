<?php

namespace App\Models\Forums;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperBoard
 */
class Board extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'description', 'icon', 'color', 'category_id', 'roles', 'parent_id', 'latest_thread_id'
    ];

    protected $casts = [
        'roles' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function subBoards(): HasMany
    {
        return $this->hasMany(Board::class, 'parent_id');
    }

    public function parentBoard(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'parent_id', 'id');
    }

    public function latestThread(): HasOne
    {
        return $this->hasOne(Thread::class)->ofMany();
    }

    public function roleHasAccess($role): bool
    {
        return empty($this->roles) || in_array($role, $this->roles);
    }

    /**
     * Get an array of parent boards for breadcrumb use
     *
     * @return array
     */
    public function getBreadcrumbsAttribute(): array
    {
        $breadcrumbs = [];

        /** @var Board|null $parentBoard */
        $parentBoard = $this->parentBoard()->select(['id', 'name'])->first();

        while ($parentBoard) {
            $breadcrumbs[] = $parentBoard;

            // Go to the next one
            $parentBoard = $parentBoard->parentBoard()->select(['id', 'name'])->first();
        }

        $breadcrumbs[] = $this;

        return $breadcrumbs;
    }
}
