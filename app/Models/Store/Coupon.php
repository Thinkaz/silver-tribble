<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperCoupon
 */
class Coupon extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'code', 'percentage', 'use_amount', 'starts_at', 'expires_at'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'expires_at' => 'date'
    ];

    public function getTimeDifferenceAttribute(): string
    {
        return $this->expires_at->diffForHumans();
    }

    public function getPackagesMappedAttribute(): string
    {
        return $this->packages->map(function ($item) {
            return $item->id;
        })->join(',');
    }

    public function packages(): MorphToMany
    {
        return $this->morphToMany(Package::class, 'packageable');
    }

    public function uses(): HasMany
    {
        return $this->hasMany(CouponUse::class);
    }
}
