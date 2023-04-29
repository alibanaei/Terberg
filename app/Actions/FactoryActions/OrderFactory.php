<?php

namespace App\Actions\FactoryActions;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderFactory implements ResourceFactory
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
