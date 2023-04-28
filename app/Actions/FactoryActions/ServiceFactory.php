<?php

namespace App\Actions\FactoryActions;

use App\Models\Service;

class ServiceFactory implements ResourceFactory
{

    function createResource(array $data): Service
    {
        return Service::create($data);
    }
}
