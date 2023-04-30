<?php

namespace App\Services\FactoryService\Implementations;

use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Models\Product;

class ProductFactoryService implements ResourceFactoryService
{

    function createResource(array $data): Product
    {
        return Product::create($data);
    }
}
