<?php

namespace App\Services\FactoryService\Implementations;

use App\Services\FactoryService\AbstractClasses\DiverseOrderFactoryService;
use App\Enums\OrderTypeEnum;

class ServiceOrderFactory extends DiverseOrderFactoryService
{
    function addOrderItems(): void
    {
        $serviceIds = $this->data['serviceIds'];
        $this->order->services()->sync($serviceIds);
    }

    function determineOrderType(): void
    {
        $this->order->type = OrderTypeEnum::Service->value;
    }
}
