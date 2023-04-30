<?php

namespace App\Services\FactoryService\Implementations;

use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Enums\OrderTypeEnum;
use App\Models\Order;

class OrderFactoryService implements ResourceFactoryService
{

    public function createResource(array $data): Order
    {
        $orderType = OrderTypeEnum::tryFrom($data['type']);

        $factory = match ($orderType){
            OrderTypeEnum::Service => new ServiceOrderFactory(),
            OrderTypeEnum::Product => new ProductOrderFactory(),
        };

        return $factory->createResource($data);
    }
}
