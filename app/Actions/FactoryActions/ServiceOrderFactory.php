<?php

namespace App\Actions\FactoryActions;

use App\Enums\OrderTypeEnum;

class ServiceOrderFactory extends DiverseOrderFactory
{
    function addOrderItems()
    {
        $serviceIds = $this->data['serviceIds'];
        $this->order->services()->sync($serviceIds);
    }

    function determineOrderType()
    {
        $this->order->type = OrderTypeEnum::Service->value;
    }
}
