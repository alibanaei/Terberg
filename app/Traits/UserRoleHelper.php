<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait UserRoleHelper
{
    public function authUserIsAdmin(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }
}
