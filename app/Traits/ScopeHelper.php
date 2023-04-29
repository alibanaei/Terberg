<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ScopeHelper
{
    use UserRoleHelper;

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive(Builder $query): void
    {
        if(! $this->authUserIsAdmin()) {
            $query->where('active', true);
        }
    }

}
