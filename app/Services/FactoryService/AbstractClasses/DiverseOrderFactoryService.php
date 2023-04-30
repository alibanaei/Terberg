<?php

namespace App\Services\FactoryService\AbstractClasses;


use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

abstract class DiverseOrderFactoryService implements ResourceFactoryService
{
    protected Order $order;

    protected array $data;

    public function __construct()
    {
        $this->order = new Order();
    }

    abstract function addOrderItems(): void;

    abstract function determineOrderType(): void;

    public function createResource(array $data): Order
    {
        $this->data = $data;

        $this->createOrder();

        $this->addOrderItems();

        $this->addOrderOptions();

        $this->calculateOrderCost();

        $this->updateOrderStatus();

        $this->order->update();

        return $this->order;
    }

    private function createOrder(): void
    {
        $this->order->user_id = Auth::id();
        $this->order->status = OrderStatusEnum::Initialized->value;
        $this->determineOrderType();

        $this->order->save();
    }

    private function addOrderOptions(): void
    {
        $optionIds = $this->data['optionIds'] ?? [];
        $this->order->options()->sync($optionIds);
    }

    private function calculateOrderCost(): void
    {
        $this->order->cost = $this->order->calculateOrderCost();
    }

    private function updateOrderStatus(): void
    {
        $this->order->status = OrderStatusEnum::Pending->value;
    }
}
