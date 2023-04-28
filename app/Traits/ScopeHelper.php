<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopeHelper
{

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

}
