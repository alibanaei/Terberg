<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\OrderCostHelper;

class Order extends Model
{
    use HasFactory, SoftDeletes, OrderCostHelper;


    protected $casts = [
        'type' => 'int'
    ];

    # region relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'order_option');
    }
    # endregion
}
