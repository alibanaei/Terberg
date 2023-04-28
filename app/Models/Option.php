<?php

namespace App\Models;

use App\Traits\ScopeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory, SoftDeletes, ScopeHelper;

    protected $fillable = [
        'name',
        'description',
        'active',
        'price'
    ];

    # region relations
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_option');
    }
    # endregion


}
