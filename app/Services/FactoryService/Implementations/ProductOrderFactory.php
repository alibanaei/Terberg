<?php

namespace App\Services\FactoryService\Implementations;

use App\Services\FactoryService\AbstractClasses\DiverseOrderFactoryService;
use App\Enums\OrderTypeEnum;

class ProductOrderFactory extends DiverseOrderFactoryService
{
    function addOrderItems(): void
    {
        $productIds = $this->data['productIds'];
        $this->order->products()->sync($productIds);
    }

    function determineOrderType(): void
    {
        $this->order->type = OrderTypeEnum::Product->value;
    }


}
