<?php

namespace App\Models;

use App\Events\RoleDeleted;
use App\Events\RoleUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * @mixin IdeHelperRole
 */
class Role extends BaseRole
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'guard_name', 'display_name', 'color', 'precedence', 'banner_image'
    ];

    protected $dispatchesEvents = [
        'updated' => RoleUpdated::class,
        'deleting' => RoleDeleted::class,
    ];
}
