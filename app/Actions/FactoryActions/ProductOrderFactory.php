<?php

namespace App\Actions\FactoryActions;

use App\Enums\OrderTypeEnum;

class ProductOrderFactory extends DiverseOrderFactory
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
