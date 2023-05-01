<?php

namespace App\Models;

use App\Traits\QueryScopeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, QueryScopeHelper;

    protected $fillable = [
        'name',
        'description',
        'active',
        'price',
        'product_type_id'
    ];


    # region relations
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }
    # endregion

}
