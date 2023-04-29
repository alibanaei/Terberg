<?php

namespace App\Actions\FactoryActions;

use App\Enums\OrderTypeEnum;

class ServiceOrderFactory extends DiverseOrderFactory
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
