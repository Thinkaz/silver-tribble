<?php

namespace App\Traits;

use App\Models\Reputation;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Reputable
{
    public function reputation(): MorphOne
    {
        return $this->morphOne(Reputation::class, 'reputable');
    }
}
