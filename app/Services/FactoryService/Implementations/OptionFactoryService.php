<?php

namespace App\Services\FactoryService\Implementations;

use App\Models\Option;
use App\Services\FactoryService\Interfaces\ResourceFactoryService;

class OptionFactoryService implements ResourceFactoryService
{

    function createResource(array $data): Option
    {
        return Option::create($data);
    }
}
