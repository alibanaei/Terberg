<?php

namespace App\Actions\FactoryActions;

use App\Enums\OrderTypeEnum;

class ProductOrderFactory extends DiverseOrderFactory
{
    function addOrderItems()
    {
        $productIds = $this->data['productIds'];
        $this->order->products()->sync($productIds);
    }

    function determineOrderType()
    {
        $this->order->type = OrderTypeEnum::Product->value;
    }


}
