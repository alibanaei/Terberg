<?php

namespace App\Actions\FactoryActions;

use App\Models\Product;

class ProductFactory implements ResourceFactory
{

    function createResource(array $data): Product
    {
        return Product::create($data);
    }
}
