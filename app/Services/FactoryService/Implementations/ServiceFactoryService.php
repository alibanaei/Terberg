<?php

namespace App\Services\FactoryService\Implementations;

use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Models\Service;

class ServiceFactoryService implements ResourceFactoryService
{

    function createResource(array $data): Service
    {
        return Service::create($data);
    }
}
